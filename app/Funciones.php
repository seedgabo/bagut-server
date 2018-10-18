<?php
namespace App;

use App\Dbf;
use App\Jobs\SendPushNotification;
use App\Models\Dispositivo;
use App\Models\Notificacion;
use App\Notifications\CambioEstado;
use App\Notifications\InvitadosChanged;
use App\Notifications\NewComentario;
use App\Notifications\NewGuardian;
use App\Notifications\NewTicket;
use App\Notifications\NewTicketGeneral;
use App\Notifications\NewUser;
use App\Notifications\PasswordChanged;
use App\Notifications\TicketVence3;
use App\Notifications\TicketVenceManual;
use App\Notifications\TicketVencido;
use App\Notifications\UpdateContenido;
use App\Notifications\UpdateVencimiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Tests\HttpCache\request;
class Funciones
{

  public static function  transdate($date, $formato ='l j \d\e F \d\e Y h:i:s A' , $diferencia = false)
  {
    if(gettype($date) == "NULL" )
    return "";
    if (gettype($date) == "string" )
    $date = Carbon::createFromFormat('Y-m-d H:i:s', $date );
    $cadena = $date->format($formato);
    $recibido = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Mon','Tue','Wed','Thu','Fri','Sat','Sun','January','February','March','April','May','June','July','August','September','October','November','December','second','seconds','minute','minutes','day','days','hour','hours','month','months','year','years','week','weeks','before','after',"of");
    $traducido = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Lun','Mar','Mie','Jue','Vie','Sab','Dom','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre','Segundo','Segundos','Minuto','Minutos','Dia','Dias','Hora','Horas','Mes','Meses','Año','Años','Semana','Semanas','Antes','Despues',"de");
    $texto = str_replace($recibido,$traducido,$cadena);
    if (ends_with($texto,"Antes"))
    {
      $texto = "Dentro de " .str_replace("Antes","",$texto);
    }
    if (ends_with($texto,"Despues"))
    {
      $texto = "Hace " .str_replace("Despues","",$texto);
    }

    if($diferencia == true)
    {
      $texto = str_replace(["Dentro de ", "Hace "],"",$texto);
    }

    return $texto;
  }

  public static function  getPDFPages($document)
  {

      // $cmd =  storage_path("pdfinfo.exe");  // Windows
      $cmd = storage_path("pdfinfo"); 
      // Parse entire output
      // Surround with double quotes if file name has spaces
      exec("$cmd \"$document\"", $output);

      // Iterate through lines
      $pagecount = 0;
      foreach($output as $op)
      {
          // Extract the number
          if(preg_match("/Pages:\s*(\d+)/i", $op, $matches) === 1)
          {
              $pagecount = intval($matches[1]);
              break;
          }
      }

      return $pagecount;
  }

  public static function sendMailUser($user)
  {
    Notification::send($user, new NewUser($user));
    Notificacion::create(['titulo' =>"bienvenido al sistema", 'mensaje' => 'No olvide cambiar su clave' ,'user_id' => $user->id]);
  }

  public static function sendMailNewTicket($ticket, $user, $guardian)
  {
    $usuarios = \App\Models\CategoriasTickets::find($ticket->categoria_id)->users()->reject(function ($usuario, $key)
      use($user,$guardian) {
        return $usuario->id == $user->id ||$usuario->id == $guardian->id  ;
    });

    if($user->id == $guardian->id)
      $lista = [$user];
    else
      $lista = [$user,$guardian];

    Notification::send($lista, new NewTicket($ticket));

    // Notification::send($usuarios, new NewTicketGeneral($ticket));


    dispatch(new SendPushNotification("Nuevo ". Lang::choice('literales.ticket', 1) ." Creado",
                          "Se ha creado un nuevo ". Lang::choice('literales.ticket', 1) ." en la categoría " .$ticket->categoria->nombre, 
                          [$user->id,$guardian->id]));    
  }

  public static function sendMailNewComentario($users, $comentario)
  {
    $usuarios = \App\User::whereIn('id',$users)->get();
    
    Notification::send($usuarios,new NewComentario($comentario));

    $usuarios = $usuarios->pluck('id');
    $ticket = $comentario->ticket;    
    dispatch(new SendPushNotification("Nuevo Seguimiento","Se ha creado un nuevo comentario en el caso " .$ticket->titulo, $usuarios));
  }

  public static function sendMailNewGuardian($guardian, $user, $ticket)
  {
    if($user->id == $guardian->id)
      $lista = [$user];
    else
      $lista = [$user,$guardian];
    Notification::send($lista, new NewGuardian($ticket,$guardian));

    dispatch(new SendPushNotification("Nuevo Responsable","Se le ha asignado el caso " .$ticket->titulo, [$user->id,$guardian->id]));
  }

  public static function sendMailPasswordChanged ($user)
  {
     Notification::send([$user], 
      new PasswordChanged());
  }

  public static function sendMailCambioEstado($guardian, $user,$ticket)
  {
    if($user->id == $guardian->id)
      $lista = [$user];
    else
      $lista = [$user,$guardian];
    Notification::send($lista, new CambioEstado($ticket));

    dispatch(new SendPushNotification("Cambio de estado","El caso " .$ticket->titulo. " ha cambiado de estado a " .$ticket->estado, [$user->id,$guardian->id]));
  }

  public static function sendMailInvitados($ticket)
  {
    $invitados = $ticket->invitados();

    Notification::send([$invitados], new InvitadosChanged($ticket));


    dispatch(new SendPushNotification("Has sido invitado a participar en un " . Lang::choice('literales.ticket', 1) ,
                          Lang::choice('literales.ticket', 1) .":" . $ticket->titulo , $ticket->invitados_id));
  }

  public static function sendMailUpdateVencimiento($user,$guardian, $ticket)
  {
    if($user->id == $guardian->id)
      $lista = [$user];
    else
      $lista = [$user,$guardian];
    Notification::send($lista, new UpdateVencimiento($ticket));

    dispatch(new SendPushNotification("Actualización de caso","Ha cambiado la fecha de plazo para el caso  " .$ticket->titulo, [$user->id,$guardian->id]));
  }


  public static function sendMailContenidoActualizado($ticket,$user,$guardian)
  {
    if($user->id == $guardian->id)
      $lista = [$user];
    else
      $lista = [$user,$guardian];
    Notification::send($lista, new UpdateContenido($ticket));

    dispatch(new SendPushNotification("Actualización de caso","El contenido del caso se actualizó: " .$ticket->titulo, [$user->id,$guardian->id]));
  }
  

  // Programados
  public static function sendMailTicketVencido($ticket)
  {
    $user = $ticket->user;
    $guardian = $ticket->guardian;
    if($user->id == $guardian->id)
      $lista = [$user];
    else
      $lista = [$user,$guardian];
    Notification::send($lista, new TicketVencido($ticket));

    Dispositivo::sendPush(Lang::choice('literales.ticket', 1) . " Por Vencer","Atención! El" . Lang::choice('literales.ticket', 1) .":  " .$ticket->titulo . "ha expirado", 
      [$user->id,$guardian->id]);
  }

  public static function sendMailTicketVence3($ticket)
  {
    $user = $ticket->user;
    $guardian = $ticket->guardian;
    if($user->id == $guardian->id)
      $lista = [$user];
    else
      $lista = [$user,$guardian];
    Notification::send($lista, new TicketVence3($ticket));

    Dispositivo::sendPush(Lang::choice('literales.ticket', 1) . " Por Vencer","Atención! El" . Lang::choice('literales.ticket', 1) .":  " .$ticket->titulo . "está a punto de llegar a su tiempo límite", 
      [$user->id,$guardian->id]);
  }

  public static function sendMailTicketVence24($ticket)
  {
    $user = $ticket->user;
    $guardian = $ticket->guardian;
    if($user->id == $guardian->id)
      $lista = [$user];
    else
      $lista = [$user,$guardian];
    Notification::send($lista, new TicketVence24($ticket));

    Dispositivo::sendPush(Lang::choice('literales.ticket', 1) . " Por Vencer","Atención! El" . Lang::choice('literales.ticket', 1) .":  " .$ticket->titulo . "vencerá en menos de 24 horas", 
      [$user->id,$guardian->id]);
  }
  
  public static function sendMailTicketVenceManual($ticket,$hours)
  {
    $user = $ticket->user;
    $guardian = $ticket->guardian;
    if($user->id == $guardian->id)
      $lista = [$user];
    else
      $lista = [$user,$guardian];

    Notification::send($lista, new TicketVenceManual($ticket,$hours));

    Dispositivo::sendPush(Lang::choice('literales.ticket', 1) . " Por Vencer","Atención! El" . Lang::choice('literales.ticket', 1) .":  " .$ticket->titulo . "vencerá en menos de". $hours . "hora(s)", 
      [$user->id,$guardian->id]);
  }

}
