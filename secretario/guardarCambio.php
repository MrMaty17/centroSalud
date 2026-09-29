<?php
include '../auth/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idSolicitud = (int)$_POST['id_solicitud'];
    $accion = $_POST['accion'];

    $solicitud = $conexion->query("
        SELECT id_turno, fecha_nueva, hora_nueva FROM solicitud_cambio WHERE id_solicitud = $idSolicitud
    ")->fetch_assoc();

    if ($accion === 'aprobar') {
        $idTurno = $solicitud['id_turno'];
        $fechaNueva = $solicitud['fecha_nueva'];
        $horaNueva = $solicitud['hora_nueva'];

        $choca = $conexion->query("
            SELECT t.id_turno FROM turno t
            WHERE t.dni_doctor = (SELECT dni_doctor FROM turno WHERE id_turno = $idTurno)
            AND t.fecha = '$fechaNueva'
            AND t.hora = '$horaNueva'
            AND t.estado != 'cancelado'
            AND t.id_turno != $idTurno
        ");

        if ($choca->num_rows > 0) {
            echo "No se pudo aprobar: ese horario ya fue ocupado por otro turno.";
        } else {
            $conexion->query("UPDATE turno SET fecha = '$fechaNueva', hora = '$horaNueva' WHERE id_turno = $idTurno");
            $conexion->query("UPDATE solicitud_cambio SET estado = 'aprobado' WHERE id_solicitud = $idSolicitud");
        }
    } elseif ($accion === 'rechazar') {
        $conexion->query("UPDATE solicitud_cambio SET estado = 'rechazado' WHERE id_solicitud = $idSolicitud");
    }
}

?>