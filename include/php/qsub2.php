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
    echo '<div class="container ">';
         echo generateQueueInformationTables();
  
   echo "<div id='left_container'>";
          echo generateQueueSettingsInputFields();
          echo genSoftwareSettings();
          echo genRecommendedSettings();
          echo genOptionalSettings(); 
          echo genValidateButton();
   echo "</div>";
    
   echo "<div id='right_container' >";
          echo generateQsubCommands();  
          echo genValidateButton();
         // echo genCopyClipboard();
          
          
   echo "</div>";
   
    echo "</div>";
}

function genValidateButton(){
        return "<button type='button' class='btn btn-info' onclick='validate(1)'>Validate Qsub Form </button> &nbsp;&nbsp;&nbsp;&nbsp;" ;
         //"<button type='button' class='btn btn-primary' onclick='download()'>Download</button><br><br>";
}

function genCopyClipboard(){
    return "<button type='button' class='btn btn-primary ' onclick='copyToClipboard()'>Copy to Clipboard</button>";
    
}


#39b3d7

function generateQueueInformationTables22(){
    
    global $conf;
    $div = "<div id='queue_info' ><legend>Available Resources</legend>";
    
    
    
    foreach(array_keys($conf['queue']) as $q) {
       $table = "<table class='queue_information_table'>\n"     ;
       $table .= "<tr>
                    <th>Queue Name</th>
                    <th>Available Processors (cpus) </th>
                    <th>Available Memory</th>
                    <th>Available Nodes</th>
                   </tr>";
       
       foreach(array_keys($conf['queue'][$q]) as $param){
           $val = $conf['queue'][$q][$param]; 
           $gb = "";
           if($param == 'default'){
                continue;
            }
            if($param == 'memory'){
                $gb.= " GB"; 
            }
            
            $table_row =  "\t<tr><td>{$param}</td><td id='{$q}_{$param}' data-param='{$param}' data-val='{$val}'>{$val}{$gb}</td></tr>\n";
            $table.= $table_row;
        }
       $div .= $table . " </table>\n" ;
    }
    return "<br>" . $div . "</div>"; 
}


/**
 *  echos a div populated with queue settings from the config.php file
 */
function generateQueueInformationTables(){
    
    global $conf;
    $div = "<div id='queue_info' > <legend>Available Queue Resources </legend>";
   
    foreach(array_keys($conf['queue']) as $q) {
       $table = "<table class='queue_information_table'>\n"     ;
       $table.= "\t<tr><th>Queue Name</th><th style='color:#269abc'>{$q}</th></tr>\n";
       foreach(array_keys($conf['queue'][$q]) as $param){
           $val = $conf['queue'][$q][$param]; 
           $gb = "";
           if($param == 'default'){
                continue;
            }
            if($param == 'memory'){
                $gb.= " GB"; 
            }
            
            
            $table_row =  "\t<tr><td>{$param}</td><td id='{$q}_{$param}' data-param='{$param}' data-val='{$val}'>{$val}{$gb}</td></tr>\n";
            $table.= $table_row;
        }
       $div .= $table . " </table>\n" ;
    }
    return "<br>" . $div . "</div><br>"; 
}




function generateQueueSettingsInputFields(){
    global $conf;
   
    $div = "<div id='queue_settings' class='row' >";
    $legend = "<legend>Choose a Queue</legend>";
    $form = "<div id='queue_form' class='col-lg-5'><form autocomplete='off' class='form-horizontal ' role='form'><fieldset>";
    $form .= $legend;
    
    //Generate Queues
    foreach(($conf['queue']) as $queue => $q_value) { 
        $checked ='';
        if(isset($conf['queue'][$queue]['default'])){
            $checked ='checked';
        }

       $form .=
               "<div class='form-group  '>
                   <label for='{$queue}' class='col-lg-5' > {$queue} queue </label> 
                         <input type='radio' class = '' name='queue' id='{$queue}' value='{$queue}' onChange='showQueueStats(\"{$queue}\")' {$checked}/ style='min-width:50px;max-width:50px;'>
              </div>";
           
    }     
    //Generate CPU dropdown, 
    //CPU, WALLTIME, CLOCK, ETC are generated via javascript by the queue settings.
    $form .="</fieldset></form></div>";
    $div2= "<div id='queue_stats'  class='col-lg-5'> </div>";
    $div .=  "<br>" . $form .  $div2 . "</div>";         

     return $div;
    
}



/**
 * echos a div with a form and sub divs of settings
 */
function genRecommendedSettings(){
 
     return "<br>" . generateSettingFields('recommended');
}

function genOptionalSettings(){
  
     return "<br>" . generateSettingFields('optional');
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
    $div = "<div id='{$type}_settings' class=' settings_div' >";
    $form = "<form  autocomplete='off' class='form-horizontal '   role='form'><fieldset>";
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
    $div = "<div id='software_settings' class='col-lg' >";
    
    
    
    $legend = "<legend>Software Run Settings </legend>";
    $form = "<form autocomplete='off' class='form-horizontal ' role='form'><fieldset>";
    $form .= $legend;

    $selectAvail  = "<select id='modules' multiple class='select2_dropdown ' onChange='addSelectElement(this.id)' onSelect='validate()'> " . getModulesSelect() . "</select>";
    $selectChosen = "<select id='modules_chosen' class=' select2_dropdown' onClick='removeSelectElement(this.id)' multiple></select>";
    
    
    $available_modules =
            "<div id='software_labels' class='form-group'>
                <div ><label class=' col-lg-2'>Available Modules</label></div>
                 <div class='col-lg-4' style='padding-left:30px'> {$selectAvail}</div><br><br><br>
           
             </div>";
             
    
    #$button = '<button type="button" class="btn btn-default plus" >+</button onClick="addCommand()">';

    $old_command = "  <input type='text' class='form-control' id='user_software' placeholder='formatdb -p F -i all_seqs.fasta -n customBLASTdb ' onKeyUp='addSoftware()'  >";
                 
    $user_software = 
            "<div class='form-group'>
                
                <label for='user_software' class='col-lg-2 ' title='user_software'>Commands to run</label>
                    <div class='col-lg-9'    > 
                        <div class='col-lg-1'>
                            <textarea class='form-control' id='user_software' rows='2' style='width:400px' placeholder='formatdb -p F -i all_seqs.fasta -n customBLASTdb ' onKeyUp='addSoftware()'></textarea>
                        </div>
                    </div >
            </div>";
    
        $form .=  $available_modules . $user_software . "</fieldset></form>";
    
    
    
    
    $div .=  $form ."</div>";
    return $div;
//modules input  
}

function generateQsubCommands(){
    global $conf;
    $div = "<legend> Your Generated <a href='http://www.clusterresources.com/torquedocs21/commands/qsub.shtml'>QSUB</a> Script</legend>";
    
    $div .= 
     "<div id='output'>
         
         #!/bin/bash<br> \n
         # ----------------QSUB Parameters----------------- #<br>\n
       <span id='qsub_params_span'>
       <!-- <span id='bash_comment'># Selects Bash as shell </span><br>\n -->
        <span id='bash_content'>#PBS -S /bin/bash</span><br>
       </span>
        # ----------------Load Modules-------------------- #<br>
        <span id='modules_span'></span>
      # ----------------Your Commands------------------- #<br>
       <span id='software_span'></span>
       </div><br>
    ";
    

    return $div;    
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
    
        $tooltip = '<a href="#" class="question" title="Parameter for this setting is -' . $parameter . ' ">?</a>';
   
        
        $div = 
        "<div class='form-group'>
           <label for='{$parameter}' class='col-lg-2 '    title='{$parameter}' >{$description} {$tooltip}  </label> 
          
                <div class='col-lg-1'>
                <input type='text' class='form-control settings_input' id='{$parameter}' placeholder='{$example}' data-special_case='{$special_case}' onkeyup='addQsubParam(this);' onChange='validate()'>
                </div>
         </div>";
        
    return $div;
    
}

function returnSpecialCase($rec_or_opt,$parameter){
    return 'You are special';
}
 





?>
 