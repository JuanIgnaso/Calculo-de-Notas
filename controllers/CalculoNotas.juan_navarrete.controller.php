<?php
declare(strict_types = 1);
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 * 
 * 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 Codigo GitHub
 */

if(isset($_POST['enviar'])){
    $data['errores'] = checkForm($_POST);
    $data['input'] = filter_var_array($_POST);
    if(count($data['errores'])==0){
        $jsonArray = json_decode($_POST['json_notas'],true);
        $resultado = datosAsignaturas($jsonArray);
        $data['resultado'] = $resultado;
    }
}

function checkForm(array $data): array{
    $errores = [];
    if(empty($data['json_notas'])){
        $errores['json_notas']= "Este campo es obligatorio";
    }else{
        $modulos = json_decode($data['json_notas'],true);
        if(json_last_error() !== JSON_ERROR_NONE){
            $errores['json_notas']= "El formato no es el correcto";
        }else{
            $erroresJson = "";
            foreach($modulos as $modulo => $alumnos){
                    if(empty($modulo)){
                        $erroresJson .= "El nombre del módulo no puede estar vacio";
                    }
                    if(!is_array($alumnos)){
                        $erroresJson .= "El modulo '". htmlentities($modulo)." no tiene un array alumnos asociado<br>";
                    }else{
                          foreach($alumnos as $alumno => $notas){
                              if(empty($alumno)){
                                  $erroresJson.= "El modulo ". htmlentities($modulo)." Tiene un alumno sin nombre<br>";
                              }
                              if(!is_array($notas)){
                                  $erroresJson .= "El alumno ".htmlentities($alumno)." No tiene un array de notas asociado en el modulo " .htmlentities($modulo)."<br>";
                              }else{
                                  foreach($notas as $nota){
                                      if(!is_numeric($nota)){
                                          $erroresJson .="El alumno ".htmlentities($alumno)." del modulo ".htmlentities($modulo). "Cuenta con una nota que no es un número<br>";
                                      }else{
                                          if($nota < 0 || $nota > 10){
                                              $erroresJson .= "El alumno ".htmlentities($alumno)." del modulo ".htmlentities($modulo). "Cuenta con una nota no permitida<br>";
                                          }
                                      }
                                     }
                              }

                         }
                    }
             
            }
            if(!empty($erroresJson)){
                $errores['json_notas'] = $erroresJson;
            }
        }
    }
    return $errores;
}

function datosAsignaturas(array $materias): array{
    $resultado = [];
    $alumnosS = [];
    
    foreach($materias as $nombreMateria => $alumnos){
        $resultado[$nombreMateria] = [];
        $aprobados = 0;
        $suspensos = 0;
        $max = [
        'alumno' => '',
         'nota' => -1
        ];
        
        $min = [
         'alumno' => '',
         'nota' => 11
        ];
        $notaAcumulada = 0;
        $contarAlumnos = 0;
        $contarNota = 0;
  
        
       foreach($alumnos as $alumno => $notas){
            $notaAcumuladaAlumno = 0;
            if(!isset($alumnosS[$alumno])){
                //Se crea el Array Alumnos para meter los alumnos con sus aprobados y sus suspensos para clasificarlos luego
                $alumnosS[$alumno] = ['aprobados' => 0, 'suspensos' => 0];
            }
           $contarAlumnos++;
           if(max($notas) > $max['nota']){
               $max['alumno'] = $alumno;
               $max['nota'] = max($notas);
           }
           if(min($notas) < $min['nota']){
               $min['alumno'] = $alumno;
               $min['nota'] = min($notas);
           }
            foreach($notas as $nota){
                $notaAcumuladaAlumno += $nota;
                $notaAcumulada += $nota;
                $contarNota++;
            }
           if(($notaAcumuladaAlumno / count($notas)) < 5){
                    $suspensos++;
                    $alumnosS[$alumno]['suspensos']++;
                }else{
                    $aprobados++;
                    $alumnosS[$alumno]['aprobados']++;
                }
        }
        if($contarAlumnos > 0){
            $resultado[$nombreMateria]['media'] = $notaAcumulada / $contarNota;
            $resultado[$nombreMateria]['max'] = $max;
            $resultado[$nombreMateria]['min'] = $min;
        }else{
            $resultado[$nombreMateria]['media'] = 0;
        }
        $resultado[$nombreMateria]['aprobados'] = $aprobados;
        $resultado[$nombreMateria]['suspensos'] = $suspensos;
    }
    
    return array ('modulos' => $resultado, 'alumnos' => $alumnosS);
}

$data['titulo'] = "Calculo de Notas";
$data["div_titulo"] = "Formulario";

include 'views/templates/header.php';
include 'views/CalculoNotas.juan-navarrete.view.php';
include 'views/templates/footer.php';

