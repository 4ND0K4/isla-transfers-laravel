<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class EmailService
{
    public function enviarEmailConLocalizador($email, $localizador, $datosReserva)
    {
        $mail = new PHPMailer(true);
        try {
            // Validar dirección de correo electrónico
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Dirección de correo electrónico no válida: ' . $email);
            }

            // Configuración de servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';  // Host de Mailtrap
            $mail->SMTPAuth = true;
            $mail->Port = 2525;  // Puerto proporcionado por Mailtrap
            $mail->Username = 'fbe99269247407';  // Usuario de Mailtrap
            $mail->Password = 'fea08499e7cfa6';  // Contraseña de Mailtrap

            $mail->setFrom('reservas@islatransfer.com', 'Isla Transfer');
            $mail->addAddress($email);

            $mail->CharSet = 'UTF-8';

            $mail->isHTML(true);
            $mail->Subject = 'Confirmación de su reserva';

            // Incrusta el logo como imagen embebida
            $mail->addEmbeddedImage(public_path('images/icons/logo_email.png'), 'logo_img');

            // Selecciona la fecha y hora según el tipo de reserva
            if ($datosReserva['id_tipo_reserva'] == 1) {
                $fecha = $datosReserva['fecha_entrada'];
                $hora = $datosReserva['hora_entrada'];
                $tipoReservaTexto = "Aeropuerto-Hotel";
            } elseif ($datosReserva['id_tipo_reserva'] == 2) {
                $fecha = $datosReserva['fecha_vuelo_salida'];
                $hora = $datosReserva['hora_vuelo_salida'];
                $tipoReservaTexto = "Hotel-Aeropuerto";
            } else {
                $fecha = "No especificada";
                $hora = "No especificada";
                $tipoReservaTexto = "Tipo de reserva desconocido";
            }

            // Contenido del email
            $mailContent = view('emails.booking_confirmation', compact('localizador', 'fecha', 'hora', 'tipoReservaTexto', 'datosReserva'))->render();
            $mail->Body = $mailContent;

            $mail->send();
            Log::info('Email enviado correctamente a ' . $email);
        } catch (Exception $e) {
            Log::error('Error al enviar el email: ' . $e->getMessage());
        }
    }
}
