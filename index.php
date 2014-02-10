<!DOCTYPE html>
<html>
<head>
    <title>Editor</title>
    <meta charset="utf-8">

    <link rel="stylesheet" type="text/css" href="assets/style.css" />
    <link rel="shortcut icon" href="assets/favicon.ico" />

    <!-- jQuery -->
    <script src="assets/js/jquery/jquery-2.1.0.min.js" type="text/javascript"></script>

    <!-- Bootstrap -->
    <script src="assets/js/bootstrap/bootstrap.min.js" type="text/javascript"></script>
    <link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css" />

    <!-- The CodeMirror -->
    <script src="assets/js/codemirror/lib/codemirror.js" type="text/javascript"></script>
    <!-- The CodeMirror Modes - note: for HTML rendering required: xml, css, javasript -->
    <script src="assets/js/codemirror/mode/xml/xml.js" type="text/javascript"></script>
    <script src="assets/js/codemirror/mode/clike/clike.js" type="text/javascript"></script>
    <script src="assets/js/codemirror/mode/javascript/javascript.js" type="text/javascript"></script>
    <script src="assets/js/codemirror/mode/css/css.js" type="text/javascript"></script>
    <script src="assets/js/codemirror/mode/php/php.js" type="text/javascript"></script>
    <script src="assets/js/codemirror/mode/htmlmixed/htmlmixed.js" type="text/javascript"></script>
    <!-- CodeMirror Addons -->
    <script src="assets/js/codemirror/addon/selection/active-line.js"></script>
    <script src="assets/js/codemirror/addon/lint/lint.js"></script><link href="assets/js/codemirror/addon/lint/lint.css" rel="stylesheet" type="text/css" />
    <!-- CodeMirror Style & Theme -->
    <link href="assets/js/codemirror/lib/codemirror.css" rel="stylesheet" type="text/css" />
    <link href="assets/js/codemirror/theme/monokai.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="navbar-brand" href="#">PHP Editor</a>
            <div class="btn-run btn-green">&#9658; Run</div>
        </div>
    </div>
</div>
<div id="wrapper">

    <div id="left"><!-- CodeMirror Textarea -->
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
</div>

<script type="text/javascript">

var widgets = []

function resetWidgets() {
	for (var i = 0; i < widgets.length; ++i)
      editor.removeLineWidget(widgets[i]);
    widgets.length = 0;
}

function resetLineClasses()
{
    lineCount = editor.doc.size;
    for (var i = 0; i < lineCount; ++i)
        editor.removeLineClass(i, "background");
}

function createLineWidget(line, message) {
	var msg = document.createElement("div");
    var icon = msg.appendChild(document.createElement("span"));
    icon.innerHTML = "!!";
    icon.className = "lint-error-icon";
    msg.appendChild(document.createTextNode(message));
    msg.className = "lint-error";

    widgets.push(editor.addLineWidget(line, msg, {coverGutter: false, noHScroll: true}));
}

    function submitCode() {
        var xhr = $.ajax({
            type: "POST",
            url: "eval.php",
            data: "code=" + editor.getValue(),
            dataType: 'text',
            success: function(res){
                // res = html result
                $("div.codemirror-result").html(res);
                // handle error
                var error = $.parseJSON( xhr.getResponseHeader("Z-Error") );
                if(error) {
                    // highlight the error line
                    editor.addLineClass(error.line, "background", "CodeMirror-highlightErrorline-background");
                    // set focus to line after the error
                    editor.setCursor({line: error.line+1});
                    editor.focus();
                    // set error line widget above the error line
                    resetWidgets();
                    createLineWidget(error.line-1, error.message);
                } else {
                	resetWidgets();
                    resetLineClasses();
                }
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
        styleActiveLine: true, /* Addon */
        mode: 'javascript',
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: 'keep',
        keyMap: 'LiveEditor',
        theme : 'monokai',
        tabMode: 'shift',
        gutters: ["CodeMirror-lint-markers", "CodeMirror-linenumbers"],
        onCursorActivity: function() {
            editor.addLineClass(hlLine, null);
            hlLine = editor.addLineClass(editor.getCursor().line, "CodeMirror-activeline-background");
        }
    });

    editor.focus();
    editor.setCursor({line: 3});
    editor.setSize('100%', '100%');
</script>
</body>