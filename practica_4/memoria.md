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
