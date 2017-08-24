<?php
	print_r('Prueba');
	exit;
	if (isset($_POST['tag'])) {
		try {
			require_once("Conexion.php");
			$sql = "SELECT * FROM bitacora";
			$result = DATA::Ejecutar($sql);
			print_r($result);
			if ($result->rowCount() > 0) {
				$json = array();
				while ($row = $result->fetch()) {
					$json[] = array(
						'Id' => $row['Id'],
						'Cedula' =>$row['Cedula'],
						'Entrada' => $row['Entrada'],
						'Salida' => $row['Salida'],
						'Detalle' => $row['Detalle']
					);
				}
				$json['success'] = true;
				echo json_encode($json);
			}
		} catch (PDOException $e) {
			echo 'Error: '. $e->getMessage();
		}
	}
?>