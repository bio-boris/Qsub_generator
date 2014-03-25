$(document).ready(function() {
    $(".select2_dropdown").select2({width: 400});
    $(".select2_dropdown").change(function() {
        validate();
    });

    selectedQueue = $("#queue_form").find(":checked").val();
    showQueueStats(selectedQueue);
    addResourcesToQsubSpan();
    
 /*   //Make Right Container Scroll
var $sdiv = $("#right_container");
$(window).scroll(function() {
    $sdiv.stop().animate({
        "marginTop": ($(window).scrollTop() + 30) + "px"
    }, "fast");
});
    
   */ 
     $(".question").popover({placement:'top',trigger:'hover',});
});


function download(){

    var output = $("#output").text();
    output = output.replace(/<br>/g,"\n");
  window.open("data:text/json;charset=utf-8," + (output));

    
    
}

function validate(msg) {
    removeSpaces();
    checkEmail();
   
    removeUnallowedCharacters();

    populateQsub();
    
    if(msg ==1){
        alert("Your input is valid.")
        
    }
}



function removeUnallowedCharacters(){
   var noSpacesArray = new Array('N','d', 'e','A');
    var correctedArray = new Array();
    for(var i=0; i < noSpacesArray.length; i++){
        var input = noSpacesArray[i];
        
        //var value =   $("#"+input).val().replace(/[^A-Za-z0-9_.-\/]/g,""); 
        var value =   $("#"+input).val().replace(/[^A-Za-z0-9_\.\-\/]/g,""); 
        if(($("#"+input).val()) != value){
            correctedArray.push(input);
        }
        
        $("#"+input).val(value); 
        $("#"+input).keyup(); //Trigger event
    }
    
    if(correctedArray.length > 0){
        alert("Removed unallowed characters for the following parameters : " + correctedArray );
    }
}



function checkEmail(){
    
    var email = $("#M").val();
    if(email){
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(! re.test(email)){
            alert("Please enter a valid email address.")
            $("#M").val("");
            
            $("#M").trigger("onkeyup");
            return false;
            
        }
    }
    return true;
}

function removeSpaces(){
    var noSpacesArray = new Array('M','N','d', 'e','A');
    var correctedArray = new Array();
    for(var i=0; i < noSpacesArray.length; i++){
        var input = noSpacesArray[i];
        var value =   $("#"+input).val().replace(/ /g,"_"); 
        
        if(($("#"+input).val()) != value){
            correctedArray.push(input);
        }
        
        $("#"+input).val(value); 
        $("#"+input).keyup(); //Trigger event
    }
    
    if(correctedArray.length > 0){
        alert("Replaced spaces with underscores for the following parameters : " + correctedArray );
    }
   
    
}

function addResourcesToQsubSpan() {
    //Get Queue
    var selectedQueue = $("#queue_form").find(":checked").val();
    var cpu = $("#CPU").find(":selected").text();
    var memory = $("#Memory").find(":selected").text();
    var nodes = $("#Nodes").find(":selected").text();
    if(nodes >1){
        nodeWarning();
    }
    
    var resources = "nodes=" + nodes + ":ppn=" + cpu + ",mem=" + memory*1000 + "mb";
    addQsubParamHelper('l', resources, 'qsub_commands');
}


function addSoftware() {
    var user_software = $("#user_software").val();
    user_software = user_software.replace(/\n/g, '<br />');
    $("#software_span").html(user_software);
}

function addModules() {
    var modules = $("#modules").select2("val");

    var content = '';
    for (var module_index = 0; module_index < modules.length; module_index++) {
        var module = modules[module_index];
        module = module.replace(/\(default\)/,"");
        content += 'module load ' + module + "<br>";
    }
    $("#modules_span").html(content);
}


function addSelectElement(select_id) {
    var element = $("#modules option:selected").text();
    var js = "onDoubleClick=\"alert('Clicked')\""
    $('#modules_chosen').append("<option  value='" + element + "'" + 1 + " >" + element + "</option>");
}

function removeSelectElement(select_id) {
    $("#module_chosen option:selected").remove();
}



function hideOptionalDiv() {
    $("#Optional_settings_inputs").toggle();
    if ($("#hideDiv").html() == 'Show') {
        $("#hideDiv").html('Hide');
    }
    else {
        $("#hideDiv").html('Show');
    }

}

function nodeWarning(){
    alert('You selected more than one node. Are you sure this is what you want?');
}


function addCommandToRun() {
    populateSpan('command')
}


//By Generating them with max values, I won't need to use  validation on memoyr/nodes/walltime etc, by forcing rules


function create_tooltip(title){
    return '<a href="#" class="question" title="'+title+'">?</a>';
}

function showQueueStats(queueName) {
    //populateSpan('l',''); //reset the memory
    
    var queue = "#" + queueName;
    var cpu = $(queue + "_cpu").html();
    var memory = $(queue + "_memory").html().match(/[0-9]+/g);
    var nodes = $(queue + "_nodes").html();
    var walltime = $(queue + "_walltime").html();
    var stats = "CPU=" + cpu + " from " + queue + "_cpu " + "Memory " + memory;

    //  Map<String, String> map = new HashMap<String, String>();
    var cpu_input = "";
    var memory_input = "";
    var nodes_input = "";
    var walltime_input = "";

    /*var nodes_tooltip = "<a href='#' class='question' id='nodes_question' "+
    "title='If you would like to use more memory than available on a single node, you must request multiple nodes. For example, if you set the node to 2, the amount of CPU and Memory  requested will be doubled'>"+
    "?</a>";
    //var nodes_tooltip = "<a href='#' title='If you would like to use more memory than available on a single node, you must request multiple nodes. For example, if you set the node to 2, the amount of CPU and Memory  requested will be doubled'>?</a>"
    */
    var cpu_tooltip   = create_tooltip("The amount of threads your software uses should match the number of cores you reserve.")
    var memory_tooltip   = create_tooltip("The amount of memory your software uses should match the number of memory you reserve.")
    var nodes_tooltip = create_tooltip("If you would like to use more memory than is available on a single node for an MPI job, you can reserve addtional nodes");
    
    //var nodes_tooltip = '<a href="#" class="question" title="If you would like to use more memory than is available on a single node for an MPI job, you can reserve addtional nodes.">?</a>';
    
//data-original-title="first tooltip"
    var cpu_div =
            "<div class='form-group'>" +
            "<label class='col-lg-5 ' >CPU (cores) &nbsp;&nbsp;" +cpu_tooltip+ "</label>" +
            returnOptionSelectHTML('CPU', cpu) +
            "</div>";

    var memory_div =
            "<div class='form-group'>" +
            "<label class='col-lg-5'>Memory (GB) &nbsp;&nbsp;" +memory_tooltip+"</label>" +
    
            returnOptionSelectHTML('Memory', memory) +
            "</div>";

    var nodes_div =
            "<div class='form-group'>" +
            "<label class='col-lg-5'>Nodes &nbsp;&nbsp;" + nodes_tooltip + "</label>" +
            returnOptionSelectHTML('Nodes', nodes) +
            "</div>";

    var legend = "<legend style='width:200px;'>Allocate Job Submission Resources</legend>"
    var html = "<form class='form-horizontal' role='form'><fieldset>" + legend + cpu_div + memory_div + nodes_div + "</fieldset></form>";
    
    addQsubParamHelper('q',queueName)
    $("#queue_stats").html(html);
    addResourcesToQsubSpan();
   
}

function returnOptionSelectHTML(name, int_max) {
    var html = "<select id='" + name + "' onChange='addResourcesToQsubSpan()' class='form-control ' style='max-width:100px'>";
    for (var i = 1; i <= int_max; i++) {
        html += "<option >" + i + "</option>\n";
    }
    html += "</select>\n";
    return html;
}


//Adds qsub_parameters
function addQsubParam(formInputElement) {
    var parameter = formInputElement.id;
    var value = formInputElement.value;
    var specialCase = formInputElement.getAttribute('data-special_case');

    if (specialCase != 0) {
        if (specialCase == 'M') {
            addQsubParamHelper(parameter, value, 'pbs');
            addQsubParamHelper('m', 'abe', 'pbs');
            if (value.length == 0) {
                addQsubParamHelper('m', '', 'pbs'); //remove 2nd abe command
            }
        }
    }
    else {
        addQsubParamHelper(parameter, value, 'pbs')
    }
}


//Adds/Modifies/Deletes a qsub_parameter span in the qsub output form
function addQsubParamHelper(parameter, value) {
    var content = "#PBS -" + parameter + " " + value + "<br>";
    var span = "<span id='" + parameter + "_param_input'>" + content + "</span>";
    var spanID = '#' + parameter + "_param_input";

    if (value.length == 0) {
        $(spanID).remove();
    }
    else {
        if ($(spanID).length != 0) {
            $(spanID).html(content);
        }
        else {
            $('#qsub_params_span').append(span);
        }
    }
}


function populateQsub() {
    //addQsubParams(); might need to check this later, but otherwise they should be ok
    addModules();
    addSoftware();
}

function copyToClipboard() {
   id = "output";
 

}



/*
function addQsubParams() {
    var elem = document.getElementById('frmMain').elements;
    for (var i = 0; i < elem.length; i++)
    {
        addQsubParam(elem[i]);
    }


}
*/
/*
function verify_email() {
                varemail = strtolower($email);
                $valid = 1;
                if (strpos($email,"@")) {
                        list($prefix,$hostname) = explode("@",$email);
                        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/",
                                $email)) {
                                $valid = 0;
                        }
                        if (($hostname != "") && (!checkdnsrr($hostname,"MX"))) {
                                $valid = 0;
                        }
                }
                else {
                        $valid = 0;
                }
                return $valid;

        } 
*/

/*
 
 function addParametersToQsubSpan(){
 //foreach qsub span recommended,optional
 //spanid= recommended,optional
 //foreach formelement in SpanID
 //extractAndCreateSpan(spanID)
 
 }
 
 function extractAndCreateSpan(id){
 var parameter   = id;
 var value       = $("#"+id).val();
 var specialCase = $("#"+id).getAttribute('data-special_case');
 
 if (specialCase != 0) {
 if (specialCase == 'M') {
 createSpan();
 
 
 }
 }
 else {
 populateSpan(parameter, value, 'pbs')
 }
 
 
 
 createSpan()
 
 }
 
 function createSpan(id,value,type){
 var prefix = type=='qsub'?'#PBS -':'';
 var spanID = id + "_" + type;
 var span = "<span id='" + spanID + "_" + type + ">" + prefix + value + "<br></span>";
 return span;
 }
 
 function insertSpan(insertedSpan,spanID){
 $("#"+spanID).html(insertedSpan);
 }
 
 */
