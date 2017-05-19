# Memoria de la Práctica 4

## Nota

Para todos los ejercicios que componen esta cuarta práctica se ha utilizado el mismo código para el main, modificándose
sólo en la última parte (en la que se especifican los cambios realizados):

```
public static void main(String[] args) throws IOException {
                if (args.length != 2) {
                        System.err.println("Usage: MinTemperature <input path> $
                        System.exit(-1);
                }
                JobConf conf = new JobConf(Min.class);
                conf.setJobName("Min temperature");
                FileInputFormat.addInputPath(conf, new Path(args[0]));
                FileOutputFormat.setOutputPath(conf, new Path(args[1]));
                conf.setMapperClass(MinMapper.class);
                conf.setReducerClass(MinReducer.class);
                conf.setOutputKeyClass(Text.class);
                conf.setOutputValueClass(DoubleWritable.class);
                JobClient.runJob(conf);
}
```
Hasta llegar al último ejercicio de esta práctica, se utiliza este mismo main usado en el ejemplo inicial de cálculo
del mínimo. Dado que el nombre de las clases es irrelevante para la consecución de los resultados en esta práctica, no se han cambiado y se han quedado con los originales del ejemplo (MinReducer,MinMapper).

## 1. Cálculo de Valor mínimo del diez por ciento de la colección ECBDL14 de la columna 5.

Se ha usado el ejemplo que ya viene incluido en las transparencias del guión de prácticas:

```
//Reducer
public class MinReducer extends MapReduceBase implements Reducer<Text,DoubleWritable, Text, DoubleWritable> {
  public void reduce(Text key, Iterator<DoubleWritable> values,OutputCollector<Text, DoubleWritable> output, Reporter reporter) throws IOException {
    Double minValue = Double.MAX_VALUE;
    while (values.hasNext()) {
        minValue = Math.min(minValue, values.next().get());
    }
    output.collect(key, new DoubleWritable(minValue));
  }
}
```

```
//Mapper
public class MinMapper extends MapReduceBase implements Mapper<LongWritable, Text, Text, DoubleWritable> {
  private static final int MISSING = 9999;
  public static int col=5;
  public void map(LongWritable key, Text value, OutputCollector<Text, DoubleWritable> output, Reporter reporter) throws IOException {
      String line = value.toString();
      String[] parts = line.split(",");
      output.collect(new Text("1"), new DoubleWritable(Double.parseDouble(parts[col])));
  }
}
```
El resultado obtenido ha sido el que refleja el guión:

```
1 -11.0
```

## 2. Cálculo de Valor máximo del diez por ciento de la colección ECBDL14 de la columna 5.

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

La clase de mapeado es la misma que he utilizado en el ejercicio anterior. Es decir: la del cálculo del mínimo. El resultado obtenido es el siguiente:

```
1	9.0
```

## 3. Cálculo del mínimo y el máximo a la vez para la columna 5

Para el cálculo de los dos valores a la vez, lo que hago es un proceso similar al seguido en los ejercicios anteriores, pero esta vez haciendo dos "collect" en vez de uno solo, para recoger los valores mínimo y máximo:

```
//Clase Reducer
public double[] calculaMaximoMinimo(Iterator<DoubleWritable> values){
      double valorAc;
      double minimo = Double.MAX_VALUE;
      double maximo = Double.MIN_VALUE;
      double[] resultado = new double[2];

      while(values.hasNext()){
              valorAc = values.next().get();
              maximo = Math.max(maximo, valorAc);
              minimo = Math.min(minimo, valorAc);
      }

      resultado[0] = maximo;
      resultado[1] = minimo;

      return resultado;
}

public void reduce(Text key, Iterator<DoubleWritable> values, OutputCollector<Text, DoubleWritable> output, Reporter reporter) throws IOException {
                double[] resultado = calculaMaximoMinimo(values);
                output.collect(new Text("Máximo"), new DoubleWritable(resultado[0]));
                output.collect(new Text("Mínimo"), new DoubleWritable(resultado[1]));
}
```
El código de la clase mapper es el siguiente:

```
public class MinMapper extends MapReduceBase implements Mapper<LongWritable, Text, Text, DoubleWritable> {
        private static final int MISSING = 9999;
        public int col=5;

                public void map(LongWritable key, Text value, OutputCollector<Text, DoubleWritable> output, Reporter reporter) throws IOException {
                String line = value.toString();
                String[] parts = line.split(",");

                output.collect(new Text("Máximo y Mínimo "), new DoubleWritable(Double.parseDouble(parts[col])));
        }
}
```

Y el resultado obtenido:

```
Máximo	9.0
Mínimo	-11.0
```

## 4. Cálculo de máximo y mínimo para todas las columnas del dataset (menos la de la variable de clase)

Es el mismo algoritmo utilizado anteriormente, pero aplicado a todas las columnas del dataset. Por ello, sólo
hay que modificar el mapper con respecto al ejercicio anterior, parametrizando el valor de las columnas, de cara a poder iterar sobre ellas y calcular la media sobre toda la colección:

```
public class MinMapper extends MapReduceBase implements Mapper<LongWritable, Text, Text, DoubleWritable> {
        private static final int MISSING = 9999;

                public void map(LongWritable key, Text value, OutputCollector<Text, DoubleWritable> output, Reporter reporter) throws IOException {
                String line = value.toString();
                String[] parts = line.split(",");

                for(int i = 0; i<10; i++){
                        output.collect(new Text("Columna "+i), new DoubleWritable(Double.parseDouble(parts[i])));
                }
        }
}
```

De cara a que los resultados sean más claros, modifico el texto definitorio de los valores máximo y mínimo que se van obteniendo, de cara saber la columna a la cuál pertenecen dichos valores:

```
output.collect(new Text(key + " Máximo"), new DoubleWritable(resultado[0]));
output.collect(new Text(key + " Mínimo"), new DoubleWritable(resultado[1]));
```

Obtengo los siguientes resultados:

```
Columna 6 Máximo	9.0
Columna 6 Mínimo	-13.0
Columna 7 Máximo	9.0
Columna 7 Mínimo	-12.0
Columna 8 Máximo	7.0
Columna 8 Mínimo	-12.0
Columna 9 Máximo	10.0
Columna 9 Mínimo	-13.0
Columna 0 Máximo	0.768
Columna 0 Mínimo	0.094
Columna 1 Máximo	0.154
Columna 1 Mínimo	0.0
Columna 2 Máximo	10.0
Columna 2 Mínimo	-12.0
Columna 3 Máximo	8.0
Columna 3 Mínimo	-11.0
Columna 4 Máximo	9.0
Columna 4 Mínimo	-12.0
Columna 5 Máximo	9.0
Columna 5 Mínimo	-11.0
```

## 5. Cálculo de Valor medio de la columna 5 del diez por ciento de la colección ECBDL14

Utilizo el mismo mapper que en el ejercicio de cálculo del mínimo, ya que se trata del mismo proceso
que se muestra en él. Por el contrario, lo que sí hay que modificar tal y como he hecho antes, es la función
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

## 6. Cálculo de la media de todas las columnas del dataset (menos la de la variable de clase)

Se ha seguido un proceso parecido al del cálculo del máximo y del mínimo para todas las columnas, pero utilizando un método diseñado para el cálculo de la media en el reducer:

```
public double calculaMedia(Iterator<DoubleWritable> values){
                double valorAc = 0;
                int acumulador = 0;

                while(values.hasNext()){
                        valorAc += values.next().get();
                        acumulador++;
                }

                return (valorAc/acumulador);
}

public void reduce(Text key, Iterator<DoubleWritable> values, OutputCollector<Text, DoubleWritable> output, Reporter reporter) throws IOException {
                double resultado = calculaMedia(values);
                output.collect(new Text(key + " Media"), new DoubleWritable(resultado));
}
```

Se utiliza el mismo mapper que en el ejercicio del cálculo del máximo y del mínimo para todas las columnas. El resultado es el siguiente:

```
Columna 6 Media	-2.293434905140485
Columna 7 Media	-1.5875789403216172
Columna 8 Media	-1.7390052924221087
Columna 9 Media	-1.6989002790625127
Columna 0 Media	0.25496195991855125
Columna 1 Media	0.05212776590932377
Columna 2 Media	-2.188240380935686
Columna 3 Media	-1.408876789776933
Columna 4 Media	-1.7528724942777865
Columna 5 Media	-1.282261707288373
```

## 7. ¿Se trata de un conjunto balanceado?

Para que el conjunto fuera balanceado debería de haber un ratio inferior a 1,5 en cuanto a diversidad de clases. Es decir, la relación de aparición de las clases debería ser parecida a un elemento de cada clase (aproximadamente). Para lograr esto, hay que modificar el algoritmo de reducción de los ejercicios anteriores para que sea calculado el número de apariciones de la clase cero y la clase uno, para después obtener el ratio que las relaciona a ambas. Añado una función al código donde se resume esta funcionalidad.

```
public double calculaRatio(Iterator<DoubleWritable> values){
                int num0 = 0;
                int num1 = 0;
                double actual;
                double resultado;

                while(values.hasNext()){
                        actual = values.next().get();
                        if(actual == 0.0){
                                num0++;
                        }else
                             	if(actual == 1.0){
                                        num1++;
                                }
                }

                if(num1 >= num0)
                        resultado = (num1+0.0)/(num0+0.0);
                else
                    	resultado = (num0+0.0)/(num1+0.0);

                return resultado;
}
```
El resultado que obtengo es el siguiente, lo cual evidencia que el conjunto es no balanceado claramente, de forma muy marcada.

```
Ratio	58.582560602010815
```

## 8. Coeficiente de correlación

Para calcular el coeficiente de correlación sigo los pasos especificados en los apuntes de la asignatura. Esto es:

* Cálculo de la media de las columnas del dataset.
* Cálculo de la covarianza.
* Cálculo de la desviación típica.
* Obtención del coeficiente de correlación lineal.

Dada la extensión del dataset con el que trabajo, la obtención de este coeficiente y la consiguiente necesidad de hacer cálculos con todas las posibles parejas de valores de todo el conjunto provoca que Hadoop tarde demasiado en realizar dicha tarea. Con vistas a paliar este inconveniente sólo calcularé el coeficiente con las dos primeras columnas del dataset. A continuación puede verse el código de la clase Mapper necesario para lograr ésto:

```
public class MinMapper extends MapReduceBase implements Mapper<LongWritable, Text, Text, Text> {
        private static final int MISSING = 9999;
        public int col=0;

                public void map(LongWritable key, Text value, OutputCollector<Text, Text> output, Reporter reporter) throws IOException {
                  String line = value.toString();
                  String[] parts = line.split(",");

                  for (int i = 0; i<(parts.length-1); i++) {
                        for (int j = (i+1); j < (parts.length-1); j++) {
                                output.collect(new Text("Media de: "+Integer.toString(i) + " y " + Integer.toString(j)), new Text(parts[i] + " " + parts[j]));
                        }
                      }
                }
}
```
A continuación se muestra el código del algoritmo reductor que tiene como fin la obtención del coeficiente de correlación lineal, previo paso de haber obtenido el resto de mediciones mencionadas anteriormente:

```
//Método de abstracción del cálculo de la correlación. Recibe un iterador que se mueve por la lista de valores Text
//con sintaxis: "valorColumna1 valorColumna2".
public double calculaCorrelacion(Iterator<Text> values){
                String valor;
                String[] pareja;
                double num1,num2;
                double media1 = 0;
                double media2 = 0;
                double acu1 = 0;
                double acu2 = 0;
                double acu3 = 0;
                int numItems = 0;

                while(values.hasNext()){
                        valor = values.next().toString();
                        System.out.println(valor);
                        pareja = valor.split(" ");

                        //Pareja de números de la columna 1 y 2, respectivamente.
                        num1 = Double.parseDouble(pareja[0]);
                        num2 = Double.parseDouble(pareja[1]);

                        media1 += num1;
                        media2 += num2;
                        acu1 += num1*num2;
                        acu2 += Math.pow(num1,2);
                        acu3 += Math.pow(num2,2);

                        numItems++;
                }

                media1 = media1/numItems;
                media2 = media2/numItems;
                double covar = (acu1/numItems)-media1*media2;
                double desv1 = Math.sqrt((acu2/numItems)-Math.pow(media1,2));
                double desv2 = Math.sqrt((acu3/numItems)-Math.pow(media2,2));

                return (covar/(desv1*desv2));
}

//Método reduce desde el que se llama a la función de cálculo del coeficiente de correlación.
public void reduce(Text key, Iterator<Text> values, OutputCollector<Text, DoubleWritable> output, Reporter reporter) throws IOException {
                double resultado = calculaCorrelacion(values);
                output.collect(key, new DoubleWritable(resultado));
}
```

Por último, también se ha modificado el main con el que llevo trabajando desde el comienzo de la pŕactica, para especificar que el mapper recibe como valor un tipo Text, ya que es ahí donde le proporcionamos el par de valores de las dos columnas de las que obtenemos el coeficiente de correlación, como un único string:

```
public static void main(String[] args) throws IOException {
                if (args.length != 2) {
                        System.err.println("Usage: MinTemperature <input path> <output path>");
                        System.exit(-1);
                }
                JobConf conf = new JobConf(Min.class);
                conf.setJobName("Min temperature");
                FileInputFormat.addInputPath(conf, new Path(args[0]));
                FileOutputFormat.setOutputPath(conf, new Path(args[1]));
                conf.setMapperClass(MinMapper.class);
                conf.setReducerClass(MinReducer.class);
                conf.setMapOutputKeyClass(Text.class);
                conf.setMapOutputValueClass(Text.class);
                conf.setOutputKeyClass(Text.class);
                conf.setOutputValueClass(DoubleWritable.class);
                JobClient.runJob(conf);
}
```
Los resultados obtenidos para cada pareja de las dos primeras columnas del dataset son los siguientes (Los dos primeros números hacen referencia a la fila de los valores que representan, dentro de la primera y segunda columnas respectivamente):

```
3 y 8	0.016130402799924542
4 y 7	0.01984291578033614
5 y 6	0.03200113594875155
3 y 9	0.01817123896585364
4 y 8	0.01224584385595619
5 y 7	0.03297998768398484
4 y 9	0.014041854998880898
5 y 8	0.015183324110128226
6 y 7	0.11488805268078417
5 y 9	0.023068393377281653
6 y 8	0.07783431570283235
6 y 9	0.1071360896407867
7 y 8	-0.3292179447994215
7 y 9	0.08936167755929571
0 y 1	-0.13589916862619886
8 y 9	0.1084960047958963
0 y 2	0.09143593108544017
0 y 3	0.07005931834468403
1 y 2	-0.003036453945878444
0 y 4	0.04742917823775334
1 y 3	0.009438349438435465
0 y 5	0.12916572713949537
1 y 4	0.05885670185754341
2 y 3	-0.01726247486762999
0 y 6	0.19252517587745802
1 y 5	0.014659977638068512
2 y 4	0.018191261366109063
0 y 7	0.1792126656558994
1 y 6	-0.031832553319748075
2 y 5	0.024182999250758484
3 y 4	0.015754379166559307
0 y 8	0.06624560106081653
1 y 7	-1.7503659083517436E-5
2 y 6	0.041153841377462724
3 y 5	0.016128930425374947
0 y 9	0.13827089964163103
1 y 8	0.01589410348968727
2 y 7	0.03814283037771738
3 y 6	0.025952003813569456
4 y 5	0.07125079800784533
1 y 9	-0.01673062344568349
2 y 8	0.025077384911599235
3 y 7	0.01879122854336587
4 y 6	0.018264386288745375
```

# Parte Opcional de la práctica 4

## Cálculo del máximo, mínimo y media de todas las columnas del conjunto (menos la de la variable de clase)

Para lograr ésto, se ha modificado el código del mapeador y del reductor del algoritmo original. En el mapper se ha parametrizado el valor de las columnas, de cara a poder iterar sobre ellas y calcular los estadísticos sobre toda la colección.

```
public class MinMapper extends MapReduceBase implements Mapper<LongWritable, Text, Text, DoubleWritable> {
        private static final int MISSING = 9999;

                public void map(LongWritable key, Text value, OutputCollector<Text, DoubleWritable> output, Reporter reporter) throws IOException {
                String line = value.toString();
                String[] parts = line.split(",");

                for(int i = 0; i<10; i++){
                        output.collect(new Text("Columna "+i), new DoubleWritable(Double.parseDouble(parts[i])));
                }
        }
}

```

Por otra parte, en el reducer se ha incluido una función adicional de cálculo de los tres estadísticos vistos hasta ahora: el mínimo, máximo y media. La lógica de esta función aglomera la de las especificadas en los anteriores ejercicios y en el ejemplo del cálculo del mínimo de las transparencias. El resultado se devuelve en forma de vector de
tres componentes en la que la primera es la media, la segunda el máximo y la tercera el mínimo. La función principal
de reducción ha quedado simplificada en una llamada a esta función adicional y la escritura de las tres componentes calculadas.

```
public class MinReducer extends MapReduceBase implements Reducer<Text, DoubleWritable, Text, DoubleWritable> {

  public double[] calculaEstadisticos(Iterator<DoubleWritable> values){
          double acumulador = 0.0;
          double minimo = Double.MAX_VALUE;
          double maximo = Double.MIN_VALUE;
          int numIters = 0;
          double[] resultado = new double[3];
          double actual;

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
          double[] resultado = calculaEstadisticos(values);
          output.collect(new Text(key + " Media"), new DoubleWritable(resultado[0]));
          output.collect(new Text(key + " Máximo"), new DoubleWritable(resultado[1]));
          output.collect(new Text(key + " Mínimo"), new DoubleWritable(resultado[2]));
  }
}
```

Obtengo el siguiente resultado (para la media):

```
Columna 6 Media	-2.293434905140485
Columna 6 Máximo	9.0
Columna 6 Mínimo	-13.0
Columna 7 Media	-1.5875789403216172
Columna 7 Máximo	9.0
Columna 7 Mínimo	-12.0
Columna 8 Media	-1.7390052924221087
Columna 8 Máximo	7.0
Columna 8 Mínimo	-12.0
Columna 9 Media	-1.6989002790625127
Columna 9 Máximo	10.0
Columna 9 Mínimo	-13.0
Columna 0 Media	0.2549619599186493
Columna 0 Máximo	0.768
Columna 0 Mínimo	0.094
Columna 1 Media	0.05212776590928511
Columna 1 Máximo	0.154
Columna 1 Mínimo	0.0
Columna 2 Media	-2.188240380935686
Columna 2 Máximo	10.0
Columna 2 Mínimo	-12.0
Columna 3 Media	-1.408876789776933
Columna 3 Máximo	8.0
Columna 3 Mínimo	-11.0
Columna 4 Media	-1.7528724942777865
Columna 4 Máximo	9.0
Columna 4 Mínimo	-12.0
Columna 5 Media	-1.282261707288373
Columna 5 Máximo	9.0
Columna 5 Mínimo	-11.0
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
