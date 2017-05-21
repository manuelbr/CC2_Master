import org.apache.hadoop.mapred.Reporter;
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

	  public double calculaMedia(Iterator<DoubleWritable> values){
           double valorAc = 0;
           int acumulador = 0;

            while(values.hasNext()){
                valorAc += values.next().get();
                acumulador++;
            }

            return (valorAc/acumulador);
    }


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

  public double calculaMinimo(Iterator<DoubleWritable> values){
    double minValue = Double.MAX_VALUE;
    while (values.hasNext()) {
      minValue = Math.min(minValue, values.next().get());
    }
    return minValue;
  }

  public double calculaMaximo(Iterator<DoubleWritable> values){
    double maxValue = Double.MIN_VALUE;
    while (values.hasNext()) {
      maxValue = Math.max(maxValue, values.next().get());
    }
    return maxValue;
  }

  public void reduce(Text key, Iterator<DoubleWritable> values, OutputCollector<Text, DoubleWritable> output, Reporter reporter) throws IOException {
                double resultado = calculaMedia(values);
                output.collect(new Text(key + " Media"), new DoubleWritable(resultado));
                //output.collect(new Text(key + " Máximo"), new DoubleWritable(resultado[1]));
                //output.collect(new Text(key + " Mínimo"), new DoubleWritable(resultado[2]));
  }
}
