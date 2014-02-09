<!DOCTYPE html>
<html>
<head>
	<title>Editor</title>
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="assets/style.css" />
	<link rel="shortcut icon" href="assets/favicon.ico" />

    <!-- jQuery -->
    <script src="assets/js/jquery/jquery-2.1.0.min.js" type="text/javascript"></script>
    <!-- The CodeMirror -->
    <!--<script src="assets/js/codemirror/lib/codemirror.js" type="text/javascript"></script>-->
    <script src="assets/js/codemirror/lib/codemirror.js" type="text/javascript"></script>
    <!-- The CodeMirror Modes
         Note: for HTML rendering required: xml, css, javasript
    -->
	<script src="assets/js/codemirror/mode/xml/xml.js" type="text/javascript"></script>
	<script src="assets/js/codemirror/mode/clike/clike.js" type="text/javascript"></script>
	<script src="assets/js/codemirror/mode/javascript/javascript.js" type="text/javascript"></script>
	<script src="assets/js/codemirror/mode/css/css.js" type="text/javascript"></script>
	<script src="assets/js/codemirror/mode/php/php.js" type="text/javascript"></script>
	<script src="assets/js/codemirror/mode/htmlmixed/htmlmixed.js" type="text/javascript"></script>
	<!-- CodeMirror Style & Theme -->
	<link href="assets/js/codemirror/lib/codemirror.css" rel="stylesheet" type="text/css" />
	<link href="assets/js/codemirror/theme/monokai.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="left">
	<!-- CodeMirror Textarea -->
    <div class="btn-run btn-green">&#9658; Run</div>
	<textarea name="code" id="code" rows="10" cols="100" autocapitalize="off" autocorrect="off" wrap="off">
<?php
print '<'.'?php' ."\n";
echo 'e'.'cho' . ' "Hello World!";' . "\n";
?></textarea>
</div>

<div id="right">
	<div class="codemirror-result"></div>
</div>

<!--<div id="center">center</div>-->

<script type="text/javascript">
function submitCode() {
    $.ajax({
        type: "POST",
        url: "eval.php",
        data: "code=" + editor.getValue(),
        dataType: 'text',
        success: function(res){
            var text = res;
            //alert("Content send. Result:" + text);
            $("div.codemirror-result").html(text);
        }
    });
}

// bind clicking the "run" button
$("div.btn-run").click(function (){
    submitCode();
});

// bind key "F9" as "submit & run code"
CodeMirror.keyMap.LiveEditor = {
	'F9': function(cm) {
		submitCode();
	},
	fallthrough: 'pcDefault'
};

var editor = CodeMirror.fromTextArea(code, {
    matchBrackets: true,
	lineNumbers: true,
	mode: 'javascript',
	indentUnit: 4,
	indentWithTabs: true,
	enterMode: 'keep',
	keyMap: 'LiveEditor',
	theme : 'monokai',
    tabMode: 'shift',
	onCursorActivity: function() {
		editor.setLineClass(hlLine, null);
		hlLine = editor.setLineClass(editor.getCursor().line, "activeline");
	}
});

//var hlLine = editor.addLineClass(0, "activeline");

editor.focus();
editor.setCursor({line: 3});
editor.setSize('100%', '100%');
</script>
</body>