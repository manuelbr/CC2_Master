/***********************************************************************************************************
Función de mapeado: Cada documento de la colección se divide en: clave y valor. La clave es el countryID del
país al que pertenece y el valor es un vector que tiene la información de la ciudad (nombre, longitud y latitud).
************************************************************************************************************/

var MapCode = function () {
  emit(this.CountryID,
	{"ciudad":[
			{
        "nombre": this.City,
        "latitud":  this.Latitude,
        "longitud":  this.Longitude
			}]
  });
};

/************************************************************************************************************
Función de reducción: Se recorren todas las ciudades para cada clave de cada país. Dado que el proceso
de agrupamiento por clave ya lo hace mongo por defecto, la entrada a la función de reducción es el countryid
de cada país y los valores son los vectores con (nombre, longitud y latitud) de cada ciudad. El proceso de
agrupamiento consiste en añadir todas las ciudades que provienen del mismo país (clave) a un vector.
*************************************************************************************************************/
var ReduceCode = function(key, arr_values){
  var mapeado = {"ciudad":[]};

	for (var i in arr_values) {
		var aux = arr_values[i];
		for (var j in aux.ciudad) {
			mapeado.ciudad.push(aux.ciudad[j]);
		}
	}

  return mapeado;
};


/***************************************************************************************************************
Función de finalización: Su funcionamiento se basa en la búsqueda del par de ciudades más cercanas para cada país,
menos para Estados Unidos. En cada país (representado por una iteración del proceso "Reduce"), se comprueba
que las dos ciudades a comparar no tengan la misma posición, ya que la colección tiene errores de este tipo por defecto.
Se calcula la distancia entre cada par de ciudades distintas como: Raíz cuadrada de (cuadrado de diferencia entre
longitudes más cuadrado de diferencia entre latitudes). Se va iterando para probar todas las combinaciones de ciudades
y voy acumulando la menor distancia hasta el momento, así como el nombre del par de ciudades al que corresponde.
****************************************************************************************************************/
var FinalizeCode = function(key, value){
  var distancia;
  var ciudad1,ciudad2,min_distancia_c1,min_distancia_c2;
  var sentinel = 100000000000000000000000000000000000;

  if(value.ciudad.length < 2){
    return {
        "error" : "Este país no tiene al menos dos ciudades"
    };
  }

  for(var i in value.ciudad){
      for(var j in value.ciudad){
        if(j>i && ((value.ciudad[i].longitud != value.ciudad[j].longitud) || (value.ciudad[i].latitud != value.ciudad[j].latitud))){
          ciudad1 = value.ciudad[i];
          ciudad2 = value.ciudad[j];
          distancia = Math.sqrt(Math.pow((ciudad1.longitud - ciudad2.longitud),2) + Math.pow((ciudad1.latitud - ciudad2.latitud),2));

          if(distancia < sentinel){
            sentinel = distancia;
            min_distancia_c1 = ciudad1.nombre;
            min_distancia_c2 = ciudad2.nombre;
          }
        }
      }
  }

  return {
    "distancia" : sentinel,
    "ciudad 1" : min_distancia_c1,
    "ciudad 2" : min_distancia_c2
  };
}

/************************************************************************************************************
Ejecución del proceso de mapeado-reducción.
*************************************************************************************************************/
db.runCommand({
    mapReduce: "cities",
    map: MapCode,
    reduce: ReduceCode,
    finalize: FinalizeCode,
    query: { CountryID: { $ne: 254 } },
    out: { merge: "resultados_ob3" }
});
