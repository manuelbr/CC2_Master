# Memoria de la Práctica 4

## Cálculo de Valor máximo del diez por ciento de la colección ECBDL14

Se ha utilizado el mismo algoritmo que se proporciona en las transparencias de la asignatura
para el cálculo del valor mínimo, pero alterándolo para centrarse en el máximo de la colección.
El map se puede quedar tal y como está, ya que el proceso de cálculo del máximo se debe realizar en
el reduce, modificando el algoritmo de reducción. Dentro del archivo fuente donde se realiza este proceso, la
función de reducción tiene que tener un código como éste:

```
public void reduce(Text key, Iterator<DoubleWritable> values, OutputCollector<Text, DoubleWritable> output, Reporter reporter) throws IOException{
                Double maxValue = Double.MIN_VALUE;
                while (values.hasNext()) {
                        maxValue = Math.max(maxValue, values.next().get());
                }
                output.collect(key, new DoubleWritable(maxValue));
}
```

El resultado obtenido es el siguiente:

```
1	9.0
```

## Cálculo de Valor medio del diez por ciento de la colección ECBDL14

Al igual que en el caso anterior, todo el código de mapeado no ha sido modificado, ya que se trata del mismo proceso
que se muestra en los ejemplos. Por el contrario, lo que sí hay que modificar tal y como he hecho antes, es la función
de reducción, para que en cada iteración acumule el valor de cada elemento de la colección, para luego dividir dicha
cantidad entre el número de iteraciones dadas (número de elementos de la colección). A continuación puede verse
el algoritmo de reducción que debería usarse.

```
public void reduce(Text key, Iterator<DoubleWritable> values, OutputCollector<Text, DoubleWritable> output, Reporter reporter) throws IOException{
      Double acumulador = 0.0;
      int numIters = 0;
      while (values.hasNext()) {
              numIters++;
              acumulador += values.next().get();
      }
      output.collect(key, new DoubleWritable(acumulador/numIters));    
}
```
El resultado obtenido ha sido el siguiente:

```
1	-1.282261707288373
```

## Cálculo del máximo, mínimo y media de todas las columnas del conjunto de datos

Para lograr ésto, se ha modificado el código del mapeador y del reductor del algoritmo original. En el mapper se ha parametrizado el valor de las columnas, de cara a poder iterar sobre ellas y calcular los estadísticos sobre toda la colección.

```
public class MinMapper extends MapReduceBase implements Mapper<LongWritable, Tele, Text, Text, DoubleWritable> {
        private static final int MISSING = 9999;
        public int col=0;

                public void map(LongWritable key, Text value, OutputCollector<Text, DoubleWritable> output, Reporter reporter) throws IOException {
                String line = value.toString();
                String[] parts = line.split(",");
                for(int i = 0; i<10; i++){
                        output.collect(new Text(Integer.toString(i+1)), new DoubleWritable(Double.parseDouble(parts[i])));
                }
        }
}
```

Por otra parte, en el reducer se ha incluido una función adicional de cálculo de los tres estadísticos vistos hasta ahora: el mínimo, máximo y media. La lógica de esta función aglomera la de las especificadas en los anteriores ejercicios y en el ejemplo del cálculo del mínimo de las transparencias. El resultado se devuelve en forma de vector de
tres componentes en la que la primera es la media, la segunda el máximo y la tercera el mínimo. La función principal
de reducción ha quedado simplificada en una llamada a esta función adicional y la escritura de una de las componentes del vector de resultados obtenido, en función de la estadística que queramos visualizar en la salida. Pese a que la visualización solo puede realizarse de uno de estos valores, se calculan los tres.

```
public class MinReducer extends MapReduceBase implements Reducer<Text, DoubleWritable, Text, DoubleWritable> {

        public Double[] calculaEstadisticos(Iterator<DoubleWritable> values){
                Double acumulador = 0.0;
                Double minimo = Double.MAX_VALUE;
                Double maximo = Double.MIN_VALUE;
                int numIters = 0;
                Double[] resultado = new Double[3];
                Double actual;

                while (values.hasNext()) {
                        actual = values.next().get();
                        numIters++;
                        acumulador += actual;
                        maximo = Math.max(maximo, actual);
                        minimo = Math.min(minimo, actual);
                }
                resultado[0] = (acumulador/numIters);
                resultado[1] = maximo;
                resultado[2] = minimo;

                return resultado;
        }

        //Pasamos resultado[0] para almacenar el valor de la media
        //Pasamos resultado[1] para almacenar el valor máximo
        //Pasamos resultado[2] para almacenar el valor mínimo
        public void reduce(Text key, Iterator<DoubleWritable> values, OutputCollector<Text, DoubleWritable> output, Reporter reporter) throws IOException {
                        Double[] resultado = calculaEstadisticos(values);
                        output.collect(key, new DoubleWritable(resultado[0]));
        }
}
```

Obtengo el siguiente resultado (para la media):

```
1	0.25496195991584547
10	-1.6989002790625127
2	0.052127765909362064
3	-2.188240380935686
4	-1.408876789776933
5	-1.7528724942777865
6	-1.282261707288373
7	-2.293434905140485
8	-1.5875789403216172
9	-1.7390052924221087
```
Ahora paso a medir los tiempos de ejecución en un ámbito diferente al de la colección del ECBDL14. Por ejemplo: la colección de higgs que tiene como ruta de localización: "/user/isaac/datasets/higgsImb10-5-fold/higgsImb10.data". Este conjunto de datos es de menor tamaño que el que llevo utilizando hasta ahora, por lo que debería de obtenerse un tiempo inferior de ejecución del algoritmo Map-Reduce:

```
Total time spent by all map tasks (ms)=17418
Total time spent by all reduce tasks (ms)=45262
```
Con la colección del ECBDL14 obtengo los siguientes tiempos de ejecución:

```
Total time spent by all map tasks (ms)=54637
Total time spent by all reduce tasks (ms)=64167
```

Tal y como se puede apreciar, con el conjunto de datos del ECBDL14, se tarda mucho más tiempo en el cálculo de los valores estadísticos que con el conjunto de Higgs. Esto es algo normal, teniendo en cuenta que este último es más grande, por lo que en definitiva, sí es remarcable la diferencia en la utilización entre estas dos colecciones. De cara a agilizar el trabajo a realizar sobre el conjunto mayor, paso a modificar el algoritmo original del reducer, para descargarlo en cuanto a trabajo. Para ello limito en el algoritmo de reducción a un millón el número de iteraciones permitidas (es decir, el número de elementos a tener en cuenta para calcular los estadísticos), teniendo en cuenta que el conjunto total tiene unos tres millones de registros:

```
Total time spent by all map tasks (ms)=55112
Total time spent by all reduce tasks (ms)=63392
```
Por ello, vemos que el tiempo dedicado a las tareas de reducción se vé decrementado de forma correspondiente, aunque no de forma significativa.
