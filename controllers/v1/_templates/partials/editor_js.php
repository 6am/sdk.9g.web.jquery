<style>
	#N9geditor {
		width: 100%;
		background-color: #535353;
        flex: 0 0 auto;
        height: 18px;
        background: url(https://raw.githubusercontent.com/RickStrahl/jquery-resizable/master/assets/hsizegrip.png) center center no-repeat #535353;
        cursor: row-resize;
	}
    .ui-resizable-n {
        top: -18px;
    }

</style>

<!--Div place holder of the CSS content -->
<div class="customCss"></div>

<div class="customJs"></div>

<div style="z-index:99999; position: relative; ">
    <div class="9geditor" style="position: fixed; display:block; bottom:0; width:100%;">
        <div class="ui-resizable-handle ui-resizable-n" id="N9geditor"></div>
        <div class="NGCeditor-container"
             style="display: flex; flex-direction: row;   overflow: hidden; touch-action: none; height:100%; ">
            <div class="NGCeditor-left"
                 style="flex: 0 0 auto; width: 300px; min-height: 200px; min-width: 150px; white-space: nowrap;background: #838383; color: white;">
                <div id="9geditorJs"
                     style="background:#1a1a1a; color:white; padding:20px;border:none;resize: none; width:100%; height:100%;"></div>
            </div>

            <div class="NGCeditor-splitter"
                 style="flex: 0 0 auto; width: 18px; background: url(https://raw.githubusercontent.com/RickStrahl/jquery-resizable/master/assets/vsizegrip.png) center center no-repeat #535353; min-height: 200px; cursor: col-resize;  ">
            </div>

            <div class="NGCeditor-right"
                 style="flex: 1 1 auto; width: 100%; min-height: 200px; min-width: 200px;background: #eee;">
                <div id="9geditorCss"
                     style="background:#1a1a1a; color:white; padding:20px;border:none;resize: none; width:100%; height:100%;"></div>
            </div>
        </div>

        <div style=" position: absolute; top: 30px; right:20px; width: 35px;">
            <button id="closescript"
                    style="padding:5px 10px; background:black; color: gray; border:1px solid gray; cursor:pointer; width: 100%; margin-top: 5px;">
                <span class="fa fa-close" style="font-size:15px"></span></button>
            <button id="runscript"
                    style="padding:5px 10px; background:black; color: gray; border:1px solid gray; cursor:pointer; width: 100%; margin-top: 5px;">
                <span class="fa fa-play"></span></button>
            <button id="savescript"
                    style="padding:5px 10px; background:black; color: gray; border:1px solid gray; cursor:pointer; width: 100%; margin-top: 5px;">
                <span class="fa fa-save"></span></button>
        </div>


    </div>
</div>

<div style=" position: fixed; bottom: 20px; right:20px; width: 35px;">
    <button id="showScripts" class="showEditors"
            style="display: none;padding:5px 10px; background:black; color: gray; border:1px solid gray; cursor:pointer; width: 100%; margin-top: 5px;">
        <span class="fa fa-angle-up" style="font-size:15px"></span></button>
</div>

<script>
    $(".NGCeditor-left").splitter({
       handleSelector: ".NGCeditor-splitter",
       resizeHeight: false
    });
    $(".9geditor").resizable({minHeight: 200, handles: {'n': '#N9geditor'} });

</script>
	
<script>
	//RUN SCRITPS
	$("body").on("click","#runscript", function(){		
		//run CSS
        var CSS = ngeditorCss.getValue();
        $('.customCss').append("<style>" + CSS + "</style>");
		
		//run JS
		var theInstructions = ngeditorJs.getValue();
		var F=new Function (theInstructions);
		return(F());
		
		
	});

	//SAVE CODE TO THE CLOUD
	$("body").on("click","#savescript", function(){
        var CSS = ngeditorCss.getValue();
        var JS = ngeditorJs.getValue();


        var  data = [];

        data.push({typeid: 1, source: JS});
        data.push({typeid: 2, source: CSS});

		//ajax to cloud
        $.ajax({
            method: "POST",
            url: "save.json",
            data: ({code : data})
        }).done(function( msg ) {
            console.log( msg );
        });

	});

	
	//TOGGLE EDITOR
	$("body").on("click","#closescript", function(){
		$(".9geditor").hide();
		$("#showScripts").css('display', 'block');
	});

	$("body").on("click","#showScripts", function(){
		$(".9geditor").show();
		$("#showScripts").css('display', 'none');
	});

</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.1/ace.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.1/ext-language_tools.js" type="text/javascript"></script>
<script>
    ace.require("ace/ext/language_tools");
    var ngeditorJs = ace.edit("9geditorJs");
    var ngeditorCss = ace.edit("9geditorCss");

    ngeditorJs.resize();
    ngeditorJs.setTheme("ace/theme/merbivore_soft");
    ngeditorJs.session.setMode("ace/mode/javascript");
    ngeditorJs.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: false
    });

    ngeditorCss.resize();
    ngeditorCss.setTheme("ace/theme/merbivore_soft");
    ngeditorCss.session.setMode("ace/mode/css");
    ngeditorCss.setOptions({		
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: false
    });
</script>