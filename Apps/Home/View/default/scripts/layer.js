//弹出框自动消失
var layer=document.createElement("div");
layer.id="layer";
function layer()
{
    var style=
    {
        background:"#f00",
        position:"absolute",
        zIndex:10,
        width:"200px",
        height:"200px",
        left:"200px",
        top:"200px"
    }
    for(var i in style)
        layer.style[i]=style[i];   
    if(document.getElementById("layer")==null)
    {
        document.body.appendChild(layer);
        setTimeout("document.body.removeChild(layer)",2000)
    }
}