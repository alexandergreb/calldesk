<!DOCTYPE html>
<html>
<body>
<h1  onclick="test('wow',this)">Click on this text!</h1>
<script>
var test =function(value,object){  
object.innerHTML=value;
};     
</script>

<button id="a1" type="button" onclick="return a1_onclick('a1')">a1</button> 

<script language="javascript" type="text/javascript">
    function a1_onclick(id) {
        document.getElementById(id).style.backgroundColor = "#F00";   
    }
</script>


</body>
</html>

