<?php

	class MetodosDiversos{
		public static $dias = array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
		public static $meses = array();
		public static $meses2 = array();
	
		public static function saberQueDiaEs($fecha){//Indica que día es, en base a una fecha
				return self::$dias[date('N', strtotime($fecha))];
		}

		public static function saberDiasFinDeSemana($inicio,$fin){//indica si dentro de un rango de fechas existen sabados y domingos
			$total=0;
			for($i=$inicio;$i<=$fin;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
				if(self::saberQueDiaEs($i) === 'Sabado' || self::saberQueDiaEs($i) === 'Domingo')
					$total+=1;
			}
			return $total;
		}

		public static function saberDiasSabado($inicio,$fin){//indica si dentro de un rango de fechas existen sabados y domingos
			$total=0;
			for($i=$inicio;$i<=$fin;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
				if(self::saberQueDiaEs($i) === 'Sabado')
					$total+=1;
			}
			return $total;
		}

		public static function calcularDiasHabiles($inicio,$fin){//indica los días que existen entre un rango de fechas  sin contar los días no habiles (sabados y domingos)
			$diff=date_diff(date_create($fin),date_create($inicio));
			return intval($diff->format("%a")) + 1 - self::saberDiasFinDeSemana($inicio,$fin);
		}

		public static function obtenerMes($mes){
			self::$meses = array(
				'enero',
				'febrero',
				'marzo',
				'abril',
				'mayo',
				'junio',
				'julio',
				'agosto',
				'septiembre',
				'octubre',
				'noviembre',
				'diciembre'
			);
			return self::$meses[$mes - 1];
		}

		public static function obtenerMesAbreviado($mes){
			self::$meses2 = array(
				'ene',
				'feb',
				'mar',
				'abr',
				'may',
				'jun',
				'jul',
				'ago',
				'sep',
				'oct',
				'nov',
				'dic'
			);
			return self::$meses2[$mes - 1];
		}

		public static function formatearFecha($fecha,$abreviado){
			if($fecha != NULL && !empty($fecha)){
				$fecha = explode ( " ", $fecha);
				$fecha =  $fecha[0];
				$fecha= explode ( "-", $fecha);

				if($abreviado)
					return $fecha[2].' '.MetodosDiversos::obtenerMesAbreviado($fecha[1]).' '.$fecha[0];
				else
					return $fecha[2].' '.MetodosDiversos::obtenerMes($fecha[1]).' '.$fecha[0];
			}
			return;
		}

		public static function tiempoRespuesta($inicio,$fin){
			$date1 = new DateTime($inicio);
			$date2 = new DateTime($fin);
			$df = $date1->diff($date2);

			$str = '';
			$str .= ($df->invert == 1) ? ' - ' : '';
			/*if ($df->y > 0) {
				$str .= ($df->y > 1) ? $df->y . ' Years ' : $df->y . ' Year ';
			} if ($df->m > 0) {
				$str .= ($df->m > 1) ? $df->m . ' Months ' : $df->m . ' Month ';
			} */
			if ($df->d > 0) 
				$str .= ($df->d > 1) ? $df->d . ' Dias ' : $df->d . ' Día ';
			if ($df->h > 0) 
				$str .= ($df->h > 1) ? $df->h . ' Horas ' : $df->h . ' Hora ';
			if ($df->i > 0) 
				$str .= ($df->i > 1) ? $df->i . ' Minutos ' : $df->i . ' Minuto ';
			if ($df->s > 0) 
				$str .= ($df->s > 1) ? $df->s . ' Segundos ' : $df->s . ' Segundo ';
			return $str;
			
		}

		public function edad($fechaInicio,$fechaFinal){

			$fecha_de_nacimiento = $fechaInicio; 
			$fecha_actual = $fechaFinal;//date ("Y-m-d"); 
	
			// separamos en partes las fechas 
			$array_nacimiento = explode ( "-", $fecha_de_nacimiento ); 
			$array_actual = explode ( "-", $fecha_actual ); 
	
			$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años 
			$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
			
			//ajuste de posible negativo en $meses 
			if ($meses < 0) { 
				--$anos; 
				$meses=$meses + 12; 
			} 
	
			return $anos; 
		}

		public static function indice($limit){ //ejem. 'LIMIT 0,20'
			$limit=explode(" ",$limit);
			$limit=explode(",",$limit[2]);
			$contador=($limit[0]/$limit[1]) + 1;
			if($contador > 1 )
				$contador = ($limit[1] * ($contador - 1)) + 1;
			return $contador;
		}
	
	
	}
