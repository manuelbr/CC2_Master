package oldapi;
import java.io.IOException;
import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.io.DoubleWritable;
import org.apache.hadoop.io.LongWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapred.MapReduceBase;
import org.apache.hadoop.mapred.Mapper;
import org.apache.hadoop.mapred.OutputCollector;
import org.apache.hadoop.mapred.Reporter;
public class MinMapper extends MapReduceBase implements Mapper<LongWritable, Text, Text, DoubleWritable> {
        private static final int MISSING = 9999;
        private int col = 5;

        public void map(LongWritable key, Text value, OutputCollector<Text, DoubleWritable> output, Reporter reporter) throws IOException {
                String line = value.toString();
                String[] parts = line.split(",");

                for(int i = 0; i<10; i++){
                        output.collect(new Text("Columna "+i), new DoubleWritable(Double.parseDouble(parts[i])));
                }
                //Para hacer el mapeado solo de la columna 5
                //Como segundo argumento del collect, se le debe pasar los datos de la columna en el formato deseado: Double, text, etc.
                //output.collect(new Text("Columna "+i), new DoubleWritable(Double.parseDouble(parts[col])));

                //En caso del cálculo de la correlación
                /*
                for (int i = 0; i<(parts.length-1); i++) {
                      for (int j = (i+1); j < (parts.length-1); j++) {
                              output.collect(new Text("Media de: "+Integer.toString(i) + " y " + Integer.toString(j)), new Text(parts[i] + " " + parts[j]));
                      }
                }
                */
        }
}
