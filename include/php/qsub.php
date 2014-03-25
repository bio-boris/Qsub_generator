<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * No spaces in job name
 * email validator, autocrrect illinois or uiuc
 * tooltips for each one
 * 
 * 
 * 
 */

/**
 * Description of qsub
 *
 * @author sadkhin2
 */
error_reporting(E_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$body = "";
main();


function main(){ 
    echo '<div class="container min800">';
    genQueueSettings();
    
    userQueueSettings();
    genSoftwareSettings();
    genRecommendedSettings();
    genOptionalSettings();
    
    genButtons();

     
    genPBS();
    generateQsubCommands();
    echo "</div>";
}

function genButtons(){
        echo "<button type='button' class='btn btn-primary' onclick='validate()'>Validate Qsub Form </button> &nbsp;&nbsp;&nbsp;&nbsp;<br><br>" ;
       // echo "<button type='button' class='btn btn-primary' onclick='download()'>Download</button><br><br>";
}


/**
 *  echos a div populated with queue settings from the config.php file
 */
function genQueueSettings(){
    echo "<br>";
    global $conf;
    $div = "<div id='queue_info' class='min800'>";
   
    foreach(array_keys($conf['queue']) as $q) {
       $table = "<table class='queue-info'>\n"     ;
       $table.= "\t<tr><td>Queue Name</td><td>{$q}</td></tr>\n";
       foreach(array_keys($conf['queue'][$q]) as $param){
            if($param == 'default'){
                continue;
            }
            $val = $conf['queue'][$q][$param];
            $table_row =  "\t<tr><td>{$param}</td><td id='{$q}_{$param}' data-param='{$param}' data-val='{$val}'>{$val}</td></tr>\n";
            $table.= $table_row;
        }
       $div .= $table . " </table>\n" ;
    }
    echo $div . "</div>"; 
}




function userQueueSettings(){
    global $conf;
    echo "<br>"; 
    $div = "<div id='queue_settings' class='row min1000' style='min-width:800;max-height:500px;overflow:hidden'>";
    $legend = "<legend>Queue Settings</legend>";
    $form = "<div id='queue_form' class='col-lg-5'><form autocomplete='off' class='form-horizontal ' role='form'><fieldset>";
    $form .= $legend;
    
    //Generate Queues
    foreach(($conf['queue']) as $queue => $q_value) { 
        $checked ='';
        if(isset($conf['queue'][$queue]['default'])){
            $checked ='checked';
        }
        
      /*  $form .= 
        "<div class='form-group'>
                 <label class='control-label col-lg-4' ><input type='radio' name='queue' id='{$queue}' value='{$queue}' onChange='showQueueStats(\"{$queue}\")' {$checked}/> {$queue} Queue </label>
            </div>";
        */
       $form .=
               '<div class="form-group">
                   <label for="'.$queue.'" class="col-lg-5 control-label">' . $queue . ' Queue</label>
                        <div class="col-lg-1" style="padding-top:5px">' .
                        " <input type='radio' name='queue' id='{$queue}' value='{$queue}' onChange='showQueueStats(\"{$queue}\")' {$checked}/ style='min-width:50px;max-width:50px;'> " .'
                    </div>
              </div>';
                 
                 
                 
            
    }     
    //Generate CPU dropdown, 
    //CPU, WALLTIME, CLOCK, ETC are generated via javascript by the queue settings.
    $form .="</fieldset></form></div>";
    $div2= "<div id='queue_stats'  class='col-lg-5'> </div>";
    $div .=  $form .  $div2 . "</div>";         

     echo $div;
    
}



/**
 * echos a div with a form and sub divs of settings
 */
function genRecommendedSettings(){
     echo "<br>";
     echo generateSettingFields('recommended');
}

function genOptionalSettings(){
   echo "<br>";
     echo generateSettingFields('optional');
}



function generateSettingFields($recommended_or_optional){
    global $conf;
    $optional_button ='';
    $type = ucfirst($recommended_or_optional);
    $style = '';
    if($type == 'Optional'){
        $optional_button = "<button id='hideDiv' onClick ='hideOptionalDiv()' type='button'>Show</button>" ;
       
        
        $style = "style='display:none'";
    }

    //
    $div = "<div id='{$type}_settings' class='col-lg' >";
    $form = "<form  autocomplete='off' class='form-horizontal ' role='form'><fieldset>";
    $legend = "<legend>{$type} Settings $optional_button  </legend>";
    $form .= $legend;
    $form .= "<div id='{$type}_settings_inputs' $style>";
    
    foreach(array_values($conf['settings'][$recommended_or_optional]) as $param){
        $form.= returnSettingsInput('rec', $param);       
    }
    $form .= "</div>";
    $form .= "</fieldset>";
    $form .= "</form>";
    $div .= $form . "</div>";
    
    return $div;
    
    
}

function getModulesSelect(){
    $lines = preg_split('/ +/',file_get_contents('http://biocluster.igb.illinois.edu/apps.txt'));
   # $select = '<option label="Select Available Module" data-locked="true" s> Select Available Module </option>';
    $select ="";
    $software = array();
    foreach($lines as $line){
        $line = trim($line);
        $matches = preg_split("/\s+/",$line);
        foreach($matches as $match){
              array_push($software,($match));

        }
    }
    sort($software);
    
    foreach( $software as $module){
        if(! (preg_match('/modules|^-|^ -/',$module)) ){
            $select .= "<option value='{$module}' >{$module}</option>";
        }
    }
    
    return $select;
}


function genSoftwareSettings(){
    $div = "<div id='SoftwareSettings' class='col-lg' >";
    
    
    
    $legend = "<legend>Choose Software Modules and Enter commands to rungs</legend>";
    $form = "<form autocomplete='off' class='form-horizontal min800' role='form'><fieldset>";
    $form .= $legend;

    $selectAvail  = "<select id='modules' multiple class='select2_dropdown ' onChange='addSelectElement(this.id)' onSelect='validate()'> " . getModulesSelect() . "</select>";
    $selectChosen = "<select id='modules_chosen' class=' select2_dropdown' onClick='removeSelectElement(this.id)' multiple></select>";
    
    
    $available_modules =
            "<div id='software_labels' class='form-group'>
                <div ><label class=' col-lg-2 control-label'>Available Modules</label></div>
                 <div class='col-lg-4'> {$selectAvail}</div><br><br><br>
           
             </div>";
             
    
    #$button = '<button type="button" class="btn btn-default plus" >+</button onClick="addCommand()">';

    $old_command = "  <input type='text' class='form-control' id='user_software' placeholder='formatdb -p F -i all_seqs.fasta -n customBLASTdb ' onKeyUp='addSoftware()'  >";
                 
    $user_software = 
            "<div class='form-group'>
                <label for='user_software' class='col-lg-2 control-label' title='user_software'>Commands to run</label>
                    <div class='col-lg-9'    > 

                        <textarea class='form-control' id='user_software' rows='2' style='width:800px' placeholder='formatdb -p F -i all_seqs.fasta -n customBLASTdb ' onKeyUp='addSoftware()'></textarea>
                    </div >
            </div>";
    
        $form .=  $available_modules . $user_software . "</fieldset></form>";
    
    
    
    
    $div .=  $form ."</div>";
    echo $div;
//modules input  
}

function generateQsubCommands(){
    global $conf;
    $div = 
     "<div id='output'>
         #!/bin/bash<br> 
         # ----------------QSUB Parameters----------------- #<br>
       <span id='qsub_params_span'>
        <span id='bash_comment'># Selects Bash as shell </span><br>
        <span id='bash_content'>#PBS -S /bin/bash</span><br>
       </span>
        # ----------------Load Modules-------------------- #<br>
        <span id='modules_span'></span>
      # ----------------Your Commands------------------- #<br>
       <span id='software_span'></span>
       </div><br>
    ";
    

    echo $div;    
    /*
     $description    = $conf['param'][$param]['description'];
        $example        = $conf['param'][$param]['$example'];
        $span = "<span id='{$param}_output'>";
        $comment = "<span id='{$param}_output_comment'>##Comment</span><br>";
        $content .= "<span id='{$param}_output_content'>#PBS {$param}</span><br>";
        $span .= $comment . $content . '</span><br>';
        $div .= $span;
    */
  }




/* Helper funciton that redirects to other functions */
function returnSettingsInput($rec_or_opt,$parameter){
    global $conf;
    $description    = $conf['param'][$parameter]['description']; 
    $example        = $conf['param'][$parameter]['example'];
    $special_case  = isset($conf['param'][$parameter]['special_case'])?$conf['param'][$parameter]['special_case']:0 ;
    $div = '';
    
    if($special_case){
        if($parameter != 'M'){
                return returnSpecialCase($rec_or_opt,$parameter);
        }
    }
    
        $div = 
        "<div class='form-group'>
            <label for='{$parameter}' class='col-lg-2 control-label'    title='{$parameter}' >{$description}</label>
                <div class='col-lg-9'>
                <input type='text' class='form-control' id='{$parameter}' placeholder='{$example}' data-special_case='{$special_case}' onkeyup='addQsubParam(this)'>
                </div>
         </div>";
        
    return $div;
    
}

function returnSpecialCase($rec_or_opt,$parameter){
    return 'You are special';
}
 

function genPBS(){
    
}







echo $body;






function userQueueSettings2(){
  global $conf;
    echo "<br>"; 
    $div = "<div id='queue_settings' class='row' >";
    $legend = "<legend>Queue Settings and Allocate Resources</legend>";
    $div .=$legend;
    
    $queue_form_span = "<span id='queue_form' class='col-lg-5'>";
    $resources_form_span = "<span id='queue_stats' class='col-lg-5'></span>";
    
    $form = "<form  autocomplete='off' class='form-group role='form'>";
    //Generate Queues
    foreach(($conf['queue']) as $queue => $q_value) { 
        $checked ='';
        if(isset($conf['queue'][$queue]['default'])){
            $checked ='checked';
        }
        
      /*  $form .= 
        "<div class='form-group'>
                 <label class='control-label col-lg-4' ><input type='radio' name='queue' id='{$queue}' value='{$queue}' onChange='showQueueStats(\"{$queue}\")' {$checked}/> {$queue} Queue </label>
            </div>";
        */
       $form .=
               '<div class="form-group">
                   <label for="'.$queue.'" class="col-lg-5 control-label">' . $queue . ' Queue</label>
                        <div class="col-lg-1" style="padding-top:5px">' .
                        " <input type='radio' name='queue' id='{$queue}' value='{$queue}' onChange='showQueueStats(\"{$queue}\")' {$checked}/> " .'
                    </div>
              </div>';
    }
    
    
    
    //Generate CPU dropdown, 
    //CPU, WALLTIME, CLOCK, ETC are generated via javascript by the queue settings.
    $form .="</fieldset></form></div>";
    
    $div .=  $form .  $div2 . "</div>";         

     echo $div;
    
}


function userQueueSettings1(){
    global $conf;
    echo "<br>"; 
    $div = "<div id='queue_settings' class='row' >";
    $legend = "<legend>Queue Settings and Allocate Resources</legend>";
    
    $queue_form_div = "<div id='queue_form' class='col-lg-5'>";
    $resources_form_div = "<div id='queue_stats' class='col-lg-5'></div>";
    
    $form = "<div id='queue_form' class='col-lg-5'><form autocomplete='off' class='form-horizontal ' role='form'><fieldset>";
    //Generate Queues
    foreach(($conf['queue']) as $queue => $q_value) { 
        $checked ='';
        if(isset($conf['queue'][$queue]['default'])){
            $checked ='checked';
        }
       $form .='<div class="form-group">
                   <label for="'.$queue.'" class="col-lg-4 control-label">' . $queue . ' Queue</label>
                        <div class="col-lg-1" style="padding-top:5px">' .
                        " <input type='radio' name='queue' id='{$queue}' value='{$queue}' onChange='showQueueStats(\"{$queue}\")' {$checked} style='min-width:50px' /> " .'
                    </div>
              </div>';
    }     
    $form .="</fieldset></form>";
    
    
    $queue_form_div.= $form . "</div>";
    
    $resources_form_div = '<div id="queue_stats" class="col-lg-6"><form class="form-horizontal " role="form"><fieldset>
                <div class="form-group">
                   <label for="Blacklight" class="col-lg-4 control-label">Blacklight Queue</label>
                        <div class="col-lg-1" style="padding-top:5px"> <input type="radio" name="queue" id="Blacklight" value="Blacklight" onchange="showQueueStats(&quot;Blacklight&quot;)"> 
                    </div>
              </div><div class="form-group">
                   <label for="Default" class="col-lg-4 control-label">Default Queue</label>
                        <div class="col-lg-1" style="padding-top:5px"> <input type="radio" name="queue" id="Default" value="Default" onchange="showQueueStats(&quot;Default&quot;)" checked=""> 
                    </div>
              </div><div class="form-group">
                   <label for="Large_Memory" class="col-lg-4 control-label">Large_Memory Queue</label>
                        <div class="col-lg-1" style="padding-top:5px"> <input type="radio" name="queue" id="Large_Memory" value="Large_Memory" onchange="showQueueStats(&quot;Large_Memory&quot;)"> 
                    </div>
              </div></fieldset></form></div>';
    
    
    echo $div . $legend . $queue_form_div . $resources_form_div . "</div>";
    
}


?>
 