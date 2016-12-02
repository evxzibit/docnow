<script>
function inputFocus(i){
    if(i.value==i.defaultValue){ i.value=""; i.style.color="#000"; }
}
function inputBlur(i){
    if(i.value==""){ i.value=i.defaultValue; i.style.color="#888"; }
}
</script>
<form method="get" action="/search/">
  <input type="text" value="Search" name="Query" onfocus="inputFocus(this)" onblur="inputBlur(this)" class="searchField">
  <input name="Submit" type="image" src="/content/blog/searchIcon.png" border="0" />
</form>
