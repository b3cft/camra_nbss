addEvent(window,"load",addFormValidation);
function addFormValidation(){
for(var i=0;(currForm=document.getElementsByTagName("form")[i]);i++){
addHelp(currForm.id,currForm.id+"helpcontainer");
formV2AddRequired(document.getElementsByClassName("required",currForm));
formV2AddRequiredMax(document.getElementsByClassNameRE("requireMax([1-9][0-9]{0,1})",currForm));
formV2AddRequiredCount(document.getElementsByClassNameRE("require([1-9][0-9]{0,1})",currForm));
formV2AddTextValidation(document.getElementsByClassName("email",currForm),"email");
formV2AddTextValidation(document.getElementsByClassName("alpha",currForm),"alpha");
formV2AddTextValidation(document.getElementsByClassName("numeric",currForm),"numeric");
formV2AddTextValidation(document.getElementsByClassName("alphaNumeric",currForm),"alphaNumeric");
formV2AddTextValidation(document.getElementsByClassNameRE("charLength([1-9][0-9]{0,2})",currForm),"charLength");
formV2AddTextValidation(document.getElementsByClassNameRE("wordLength([1-9][0-9]{0,2})",currForm),"wordLength");
addCustomFunctions(currForm);
}
}
function addCustomFunctions(_2){
alert(_2.town_id.options.selectedIndex);
addEvent(_2.town_id,"click",_2.submit);
}
function formV2AddRequired(_3){
var _4=new Array("input","textarea");
for(var i=0;(type=_4[i]);i++){
for(var j=0;(div=_3[j]);j++){
if(!isUndefined(div)){
var _7=div.getElementsByTagName(type);
if(_7.length==1){
switch(_7[0].type){
case "text":
case "textarea":
addEvent(_7[0],"blur",formV2TextRequired);
break;
case "radio":
case "checkbox":
addEvent(_7[0],"blur",formV2CheckRequired);
addEvent(_7[0],"click",formV2CheckRequired);
break;
}
}else{
for(var k=0;(opt=_7[k]);k++){
addEvent(opt,"change",formV2CheckRequired);
addEvent(opt,"click",formV2CheckRequired);
}
}
}
var _9=div.getElementsByTagName("select");
if(_9.length==1){
addEvent(_9[0],"change",formV2SelectRequired);
}
}
}
}
function formV2AddRequiredCount(_a){
for(var j=0;(div=_a[j]);j++){
if(!isUndefined(div)){
var _c=div.getElementsByTagName("input");
div.className.match(new RegExp("\\brequire([1-9][0-9]{0,1})\\b"));
var _d=RegExp.$1;
if(_d<=_c.length){
for(var l=0;(opt=_c[l]);l++){
addEvent(opt,"click",formV2CheckRequiredCount);
}
div.className+=" required";
}else{
}
}
}
}
function formV2AddRequiredMax(_f){
for(var j=0;(div=_f[j]);j++){
if(!isUndefined(div)){
var _11=div.getElementsByTagName("input");
div.className.match(new RegExp("\\brequireMax([1-9][0-9]{0,1})\\b"));
var _12=RegExp.$1;
if(_12<=_11.length){
for(var l=0;(opt=_11[l]);l++){
addEvent(opt,"click",formV2CheckRequiredMax);
}
div.className+=" required";
}else{
}
}
}
}
function formV2AddTextValidation(_14,_15){
var _16=new Array("input","textarea");
for(var i=0;(type=_16[i]);i++){
for(var j=0;(div=_14[j]);j++){
if(!isUndefined(div)){
var _19=div.getElementsByTagName(type);
if(_19.length==1){
if(_19[0].type=="text"||_19[0].type=="textarea"){
switch(_15){
case "email":
inputValidation=formV2TextEmail;
break;
case "alpha":
inputValidation=formV2TextAlpha;
break;
case "numeric":
inputValidation=formV2TextNumeric;
break;
case "alphaNumeric":
inputValidation=formV2TextAlphaNumeric;
break;
case "charLength":
inputValidation=formV2TextCharLength;
break;
case "wordLength":
inputValidation=formV2TextWordLength;
break;
default:
inputValidation=false;
}
if(inputValidation!==false){
addEvent(_19[0],"blur",inputValidation);
}
}
}
}
}
}
}
function isEmail(str){
if(str.match(/^[\w-\.\']{1,}\@([\da-zA-Z-]{1,}\.){1,}[\da-zA-Z-]{2,}$/)){
return true;
}
return false;
}
function formV2TextRequired(e){
var _1c=getSource(e);
var _1d=_1c.parentNode;
if(_1c.value){
formV2ChangeLabelClass(_1d,"req","complete");
}else{
formV2ChangeLabelClass(_1d,"req","caution");
}
}
function formV2TextEmail(e){
var _1f=getSource(e);
var _20=_1f.parentNode;
if(_1f.value&&isEmail(_1f.value)){
formV2ChangeLabelClass(_20,"email","complete");
}else{
if(_1f.value){
formV2ChangeLabelClass(_20,"email","caution");
}else{
formV2ChangeLabelClass(_20,"email","");
}
}
}
function formV2TextAlpha(e){
var _22=getSource(e);
var _23=_22.parentNode;
if(_22.value&&_22.value.match(/^[a-zA-Z]{1,}$/)){
formV2ChangeLabelClass(_23,"alpha","complete");
}else{
if(_22.value){
formV2ChangeLabelClass(_23,"alpha","caution");
}else{
formV2ChangeLabelClass(_23,"alpha","");
}
}
}
function formV2TextNumeric(e){
var _25=getSource(e);
var _26=_25.parentNode;
if(_25.value&&isNumber(_25.value)){
formV2ChangeLabelClass(_26,"num","complete");
}else{
if(_25.value){
formV2ChangeLabelClass(_26,"num","caution");
}else{
formV2ChangeLabelClass(_26,"num","");
}
}
}
function formV2TextAlphaNumeric(e){
var _28=getSource(e);
var _29=_28.parentNode;
if(_28.value&&_28.value.match(/^([0-9a-zA-Z]{1,})$/)){
formV2ChangeLabelClass(_29,"alphaNum","complete");
}else{
if(_28.value){
formV2ChangeLabelClass(_29,"alphaNum","caution");
}else{
formV2ChangeLabelClass(_29,"alphaNum","");
}
}
}
function formV2TextCharLength(e){
var _2b=getSource(e);
var _2c=_2b.parentNode;
_2c.className.match(new RegExp("\\bcharLength([1-9][0-9]{0,2})\\b"));
var _2d=RegExp.$1;
if(_2b.value.length>_2d){
formV2ChangeLabelClass(_2c,"charLen","caution");
}else{
formV2ChangeLabelClass(_2c,"charLen","");
}
}
function formV2TextWordLength(e){
var _2f=getSource(e);
var _30=_2f.parentNode;
var _31=_2f.value.split(" ").length;
_30.className.match(new RegExp("\\bwordLength([1-9][0-9]{0,2})\\b"));
var _32=RegExp.$1;
if(_31>_32){
formV2ChangeLabelClass(_30,"wordLen","caution");
}else{
if(_2f.value){
formV2ChangeLabelClass(_30,"wordLen","");
}
}
}
function formV2SelectRequired(e){
var _34=getSource(e);
var _35=_34.parentNode;
if(!_34.options[_34.options.selectedIndex].value){
formV2ChangeLabelClass(_35,"req","caution");
}else{
formV2ChangeLabelClass(_35,"req","complete");
}
}
function formV2CheckRequired(e){
var _37=getSource(e);
var _38=_37.parentNode;
if(_38.className.match(new RegExp("\\b[checkbox|radio]\\b"))==null){
_38=_38.parentNode.parentNode;
}
var _39=false;
field=_37.form.elements[_37.name];
for(var i=0;i<field.length;i++){
if(field[i].checked){
_39=true;
}
}
if(_39){
formV2ChangeLabelClass(_38,"req","complete");
}else{
formV2ChangeLabelClass(_38,"req","caution");
}
}
function formV2CheckRequiredCount(e){
var _3c=getSource(e);
var _3d=_3c.parentNode;
cls=" "+_3d.className+" ";
if(cls.indexOf(" checkbox ")==-1){
_3d=_3d.parentNode.parentNode;
}
if(_3d.className.match(new RegExp("\\brequire([1-9][0-9]{0,})\\b"))!=null){
var _3e=RegExp.$1;
}
var _3f=0;
field=_3c.form.elements[_3c.name];
for(var i=0;i<field.length;i++){
if(field[i].checked){
_3f++;
}
}
if(_3f>=_3e){
formV2ChangeLabelClass(_3d,"reqCount","complete");
}else{
formV2ChangeLabelClass(_3d,"reqCount","caution");
}
}
function formV2CheckRequiredMax(e){
var _42=getSource(e);
var _43=_42.parentNode;
cls=" "+_43.className+" ";
if(cls.indexOf(" checkbox ")==-1){
_43=_43.parentNode.parentNode;
}
if(_43.className.match(new RegExp("\\brequireMax([1-9][0-9]{0,1})\\b"))!=null){
var _44=RegExp.$1;
}
var _45=0;
field=_42.form.elements[_42.name];
for(var i=0;i<field.length;i++){
if(field[i].checked){
_45++;
}
}
if(_45<=_44){
formV2ChangeLabelClass(_43,"reqMax","complete");
}else{
formV2ChangeLabelClass(_43,"reqMax","caution");
}
}
function formV2ChangeLabelClass(_47,_48,_49){
switch(_49){
case "caution":
if(_47.className.indexOf(_48+"complete")!=-1){
_47.className=_47.className.replace(new RegExp("\\b"+_48+"complete\\b"),_48+"caution");
}else{
if(_47.className.indexOf(_48+"caution")==-1){
_47.className+=" "+_48+"caution";
}
}
break;
case "complete":
if(_47.className.indexOf(_48+"caution")!=-1){
_47.className=_47.className.replace(new RegExp("\\b"+_48+"caution\\b"),_48+"complete");
}else{
if(_47.className.indexOf(_48+"complete")==-1){
_47.className+=" "+_48+"complete";
}
}
break;
case "":
_47.className=_47.className.replace(new RegExp("\\b"+_48+"complete\\b"),"");
_47.className=_47.className.replace(new RegExp("\\b"+_48+"caution\\b"),"");
break;
}
}
function addHelp(_4a,_4b){
var _4c,_4d;
if(document.getElementById&&document.appendChild&&document.removeChild){
var _4e=document.getElementById(_4a);
var _4f=_4e.getElementsByTagName("a");
for(var _50=0;_50<_4f.length;_50++){
_4c=getIDFromHref(_4f[_50].href);
_4d=document.getElementById(_4c);
_4d.style.display="none";
_4f[_50].onclick=function(_51){
return expandHelp(this,_51);
};
_4f[_50].onkeypress=function(_52){
return expandHelp(this,_52);
};
_4f[_50].parentNode.appendChild(_4d);
}
var _53=document.getElementById(_4b);
_53.parentNode.removeChild(_53);
_4e=null;
_4d=null;
_4f=null;
}
}
function getIDFromHref(_54){
var _55=_54.indexOf("#")+1;
var _56=_54.length;
return _54.substring(_55,_56);
}
function expandHelp(_57,_58){
var _59;
if(_58&&_58.type=="keypress"){
if(_58.keyCode){
_59=_58.keyCode;
}else{
if(_58.which){
_59=_58.which;
}
}
if(_59!=13&&_59!=32){
return true;
}
}
strID=getIDFromHref(_57.href);
objHelp=document.getElementById(strID);
if(objHelp.style.display=="none"){
objHelp.style.display="block";
}else{
objHelp.style.display="none";
}
return false;
}
function addEvent(obj,_5b,fn){
if(obj.attachEvent){
obj["e"+_5b+fn]=fn;
obj[_5b+fn]=function(){
obj["e"+_5b+fn](window.event);
};
obj.attachEvent("on"+_5b,obj[_5b+fn]);
}else{
obj.addEventListener(_5b,fn,false);
}
}
function removeEvent(obj,_5e,fn){
if(obj.detachEvent){
obj.detachEvent("on"+_5e,obj[_5e+fn]);
obj[_5e+fn]=null;
}else{
obj.removeEventListener(_5e,fn,false);
}
}
String.prototype.trim=function(){
r=/^\s+|\s+$/,a=this.split(/\n/g),i=a.length;
while(i-->0){
a[i]=a[i].replace(r,"");
}
return a.join("\n");
};
function removeChildren(_60){
var _61,i,_63;
var _64=Array();
for(i=0;(_61=_60.childNodes[i]);i++){
_64[i]=_61;
}
for(i=0;(_63=_64[i]);i++){
_60.removeChild(_63);
}
}
function isAlien(a){
return isObject(a)&&typeof a.constructor!="function";
}
function isArray(a){
return isObject(a)&&a.constructor==Array;
}
function isBoolean(a){
return typeof a=="boolean";
}
function isEmpty(o){
var i,v;
if(isObject(o)){
for(i in o){
v=o[i];
if(isUndefined(v)&&isFunction(v)){
return false;
}
}
}
return true;
}
function isFunction(a){
return typeof a=="function";
}
function isNull(a){
return typeof a=="object"&&!a;
}
function isNumber(a){
return typeof a=="number"&&isFinite(a);
}
function isObject(a){
return (a&&typeof a=="object")||isFunction(a);
}
function isString(a){
return typeof a=="string";
}
function isUndefined(a){
return typeof a=="undefined";
}
function addEvent(obj,_72,fn){
if(obj.addEventListener){
obj.addEventListener(_72,fn,false);
return true;
}else{
if(obj.attachEvent){
var r=obj.attachEvent("on"+_72,fn);
return r;
}else{
return false;
}
}
}
function getSource(e){
if(typeof e=="undefined"){
var e=window.event;
}
var _76;
if(typeof e.target!="undefined"){
_76=e.target;
}else{
if(typeof e.srcElement!="undefined"){
_76=e.srcElement;
}else{
return false;
}
}
return _76;
}
document.getElementsByClassName=function(_77,_78){
if(isUndefined(_78)){
_78=document;
}
var _79=_78.getElementsByTagName("*");
var _7a=new Array();
var i;
var j;
for(i=0;i<_79.length;i++){
var c=" "+_79[i].className+" ";
if(c.indexOf(" "+_77+" ")!=-1){
_7a.push(Element.extend(_79[i]));
}
}
return _7a;
};
document.getElementsByClassNameRE=function(_7e,_7f){
if(isUndefined(_7f)){
_7f=document;
}
var _80=_7f.getElementsByTagName("*");
var _81=new Array();
var i;
var j;
for(i=0;i<_80.length;i++){
if(_80[i].className.match(new RegExp("\\b"+_7e+"\\b"))!=null){
_81.push(Element.extend(_80[i]));
}
}
return _81;
};
var Prototype={Version:"1.5.0_rc0",ScriptFragment:"(?:<script.*?>)((\n|\r|.)*?)(?:</script>)",emptyFunction:function(){
},K:function(x){
return x;
}};
var Class={create:function(){
return function(){
this.initialize.apply(this,arguments);
};
}};
var Abstract=new Object();
Object.extend=function(_85,_86){
for(var _87 in _86){
_85[_87]=_86[_87];
}
return _85;
};
Object.inspect=function(_88){
try{
if(_88==undefined){
return "undefined";
}
if(_88==null){
return "null";
}
return _88.inspect?_88.inspect():_88.toString();
}
catch(e){
if(e instanceof RangeError){
return "...";
}
throw e;
}
};
Function.prototype.bind=function(){
var _89=this,_8a=$A(arguments),_8b=_8a.shift();
return function(){
return _89.apply(_8b,_8a.concat($A(arguments)));
};
};
Function.prototype.bindAsEventListener=function(_8c){
var _8d=this;
return function(_8e){
return _8d.call(_8c,_8e||window.event);
};
};
Object.extend(Number.prototype,{toColorPart:function(){
var _8f=this.toString(16);
if(this<16){
return "0"+_8f;
}
return _8f;
},succ:function(){
return this+1;
},times:function(_90){
$R(0,this,true).each(_90);
return this;
}});
var Try={these:function(){
var _91;
for(var i=0;i<arguments.length;i++){
var _93=arguments[i];
try{
_91=_93();
break;
}
catch(e){
}
}
return _91;
}};
var PeriodicalExecuter=Class.create();
PeriodicalExecuter.prototype={initialize:function(_94,_95){
this.callback=_94;
this.frequency=_95;
this.currentlyExecuting=false;
this.registerCallback();
},registerCallback:function(){
setInterval(this.onTimerEvent.bind(this),this.frequency*1000);
},onTimerEvent:function(){
if(!this.currentlyExecuting){
try{
this.currentlyExecuting=true;
this.callback();
}
finally{
this.currentlyExecuting=false;
}
}
}};
Object.extend(String.prototype,{gsub:function(_96,_97){
var _98="",_99=this,_9a;
_97=arguments.callee.prepareReplacement(_97);
while(_99.length>0){
if(_9a=_99.match(_96)){
_98+=_99.slice(0,_9a.index);
_98+=(_97(_9a)||"").toString();
_99=_99.slice(_9a.index+_9a[0].length);
}else{
_98+=_99,_99="";
}
}
return _98;
},sub:function(_9b,_9c,_9d){
_9c=this.gsub.prepareReplacement(_9c);
_9d=_9d===undefined?1:_9d;
return this.gsub(_9b,function(_9e){
if(--_9d<0){
return _9e[0];
}
return _9c(_9e);
});
},scan:function(_9f,_a0){
this.gsub(_9f,_a0);
return this;
},truncate:function(_a1,_a2){
_a1=_a1||30;
_a2=_a2===undefined?"...":_a2;
return this.length>_a1?this.slice(0,_a1-_a2.length)+_a2:this;
},strip:function(){
return this.replace(/^\s+/,"").replace(/\s+$/,"");
},stripTags:function(){
return this.replace(/<\/?[^>]+>/gi,"");
},stripScripts:function(){
return this.replace(new RegExp(Prototype.ScriptFragment,"img"),"");
},extractScripts:function(){
var _a3=new RegExp(Prototype.ScriptFragment,"img");
var _a4=new RegExp(Prototype.ScriptFragment,"im");
return (this.match(_a3)||[]).map(function(_a5){
return (_a5.match(_a4)||["",""])[1];
});
},evalScripts:function(){
return this.extractScripts().map(function(_a6){
return eval(_a6);
});
},escapeHTML:function(){
var div=document.createElement("div");
var _a8=document.createTextNode(this);
div.appendChild(_a8);
return div.innerHTML;
},unescapeHTML:function(){
var div=document.createElement("div");
div.innerHTML=this.stripTags();
return div.childNodes[0]?div.childNodes[0].nodeValue:"";
},toQueryParams:function(){
var _aa=this.match(/^\??(.*)$/)[1].split("&");
return _aa.inject({},function(_ab,_ac){
var _ad=_ac.split("=");
_ab[_ad[0]]=_ad[1];
return _ab;
});
},toArray:function(){
return this.split("");
},camelize:function(){
var _ae=this.split("-");
if(_ae.length==1){
return _ae[0];
}
var _af=this.indexOf("-")==0?_ae[0].charAt(0).toUpperCase()+_ae[0].substring(1):_ae[0];
for(var i=1,len=_ae.length;i<len;i++){
var s=_ae[i];
_af+=s.charAt(0).toUpperCase()+s.substring(1);
}
return _af;
},inspect:function(){
return "'"+this.replace(/\\/g,"\\\\").replace(/'/g,"\\'")+"'";
}});
String.prototype.gsub.prepareReplacement=function(_b3){
if(typeof _b3=="function"){
return _b3;
}
var _b4=new Template(_b3);
return function(_b5){
return _b4.evaluate(_b5);
};
};
String.prototype.parseQuery=String.prototype.toQueryParams;
var Template=Class.create();
Template.Pattern=/(^|.|\r|\n)(#\{(.*?)\})/;
Template.prototype={initialize:function(_b6,_b7){
this.template=_b6.toString();
this.pattern=_b7||Template.Pattern;
},evaluate:function(_b8){
return this.template.gsub(this.pattern,function(_b9){
var _ba=_b9[1];
if(_ba=="\\"){
return _b9[2];
}
return _ba+(_b8[_b9[3]]||"").toString();
});
}};
var $break=new Object();
var $continue=new Object();
var Enumerable={each:function(_bb){
var _bc=0;
try{
this._each(function(_bd){
try{
_bb(_bd,_bc++);
}
catch(e){
if(e!=$continue){
throw e;
}
}
});
}
catch(e){
if(e!=$break){
throw e;
}
}
},all:function(_be){
var _bf=true;
this.each(function(_c0,_c1){
_bf=_bf&&!!(_be||Prototype.K)(_c0,_c1);
if(!_bf){
throw $break;
}
});
return _bf;
},any:function(_c2){
var _c3=true;
this.each(function(_c4,_c5){
if(_c3=!!(_c2||Prototype.K)(_c4,_c5)){
throw $break;
}
});
return _c3;
},collect:function(_c6){
var _c7=[];
this.each(function(_c8,_c9){
_c7.push(_c6(_c8,_c9));
});
return _c7;
},detect:function(_ca){
var _cb;
this.each(function(_cc,_cd){
if(_ca(_cc,_cd)){
_cb=_cc;
throw $break;
}
});
return _cb;
},findAll:function(_ce){
var _cf=[];
this.each(function(_d0,_d1){
if(_ce(_d0,_d1)){
_cf.push(_d0);
}
});
return _cf;
},grep:function(_d2,_d3){
var _d4=[];
this.each(function(_d5,_d6){
var _d7=_d5.toString();
if(_d7.match(_d2)){
_d4.push((_d3||Prototype.K)(_d5,_d6));
}
});
return _d4;
},include:function(_d8){
var _d9=false;
this.each(function(_da){
if(_da==_d8){
_d9=true;
throw $break;
}
});
return _d9;
},inject:function(_db,_dc){
this.each(function(_dd,_de){
_db=_dc(_db,_dd,_de);
});
return _db;
},invoke:function(_df){
var _e0=$A(arguments).slice(1);
return this.collect(function(_e1){
return _e1[_df].apply(_e1,_e0);
});
},max:function(_e2){
var _e3;
this.each(function(_e4,_e5){
_e4=(_e2||Prototype.K)(_e4,_e5);
if(_e3==undefined||_e4>=_e3){
_e3=_e4;
}
});
return _e3;
},min:function(_e6){
var _e7;
this.each(function(_e8,_e9){
_e8=(_e6||Prototype.K)(_e8,_e9);
if(_e7==undefined||_e8<_e7){
_e7=_e8;
}
});
return _e7;
},partition:function(_ea){
var _eb=[],_ec=[];
this.each(function(_ed,_ee){
((_ea||Prototype.K)(_ed,_ee)?_eb:_ec).push(_ed);
});
return [_eb,_ec];
},pluck:function(_ef){
var _f0=[];
this.each(function(_f1,_f2){
_f0.push(_f1[_ef]);
});
return _f0;
},reject:function(_f3){
var _f4=[];
this.each(function(_f5,_f6){
if(!_f3(_f5,_f6)){
_f4.push(_f5);
}
});
return _f4;
},sortBy:function(_f7){
return this.collect(function(_f8,_f9){
return {value:_f8,criteria:_f7(_f8,_f9)};
}).sort(function(_fa,_fb){
var a=_fa.criteria,b=_fb.criteria;
return a<b?-1:a>b?1:0;
}).pluck("value");
},toArray:function(){
return this.collect(Prototype.K);
},zip:function(){
var _fe=Prototype.K,_ff=$A(arguments);
if(typeof _ff.last()=="function"){
_fe=_ff.pop();
}
var _100=[this].concat(_ff).map($A);
return this.map(function(_101,_102){
return _fe(_100.pluck(_102));
});
},inspect:function(){
return "#<Enumerable:"+this.toArray().inspect()+">";
}};
Object.extend(Enumerable,{map:Enumerable.collect,find:Enumerable.detect,select:Enumerable.findAll,member:Enumerable.include,entries:Enumerable.toArray});
var $A=Array.from=function(_103){
if(!_103){
return [];
}
if(_103.toArray){
return _103.toArray();
}else{
var _104=[];
for(var i=0;i<_103.length;i++){
_104.push(_103[i]);
}
return _104;
}
};
Object.extend(Array.prototype,Enumerable);
if(!Array.prototype._reverse){
Array.prototype._reverse=Array.prototype.reverse;
}
Object.extend(Array.prototype,{_each:function(_106){
for(var i=0;i<this.length;i++){
_106(this[i]);
}
},clear:function(){
this.length=0;
return this;
},first:function(){
return this[0];
},last:function(){
return this[this.length-1];
},compact:function(){
return this.select(function(_108){
return _108!=undefined||_108!=null;
});
},flatten:function(){
return this.inject([],function(_109,_10a){
return _109.concat(_10a&&_10a.constructor==Array?_10a.flatten():[_10a]);
});
},without:function(){
var _10b=$A(arguments);
return this.select(function(_10c){
return !_10b.include(_10c);
});
},indexOf:function(_10d){
for(var i=0;i<this.length;i++){
if(this[i]==_10d){
return i;
}
}
return -1;
},reverse:function(_10f){
return (_10f!==false?this:this.toArray())._reverse();
},inspect:function(){
return "["+this.map(Object.inspect).join(", ")+"]";
}});
var Hash={_each:function(_110){
for(var key in this){
var _112=this[key];
if(typeof _112=="function"){
continue;
}
var pair=[key,_112];
pair.key=key;
pair.value=_112;
_110(pair);
}
},keys:function(){
return this.pluck("key");
},values:function(){
return this.pluck("value");
},merge:function(hash){
return $H(hash).inject($H(this),function(_115,pair){
_115[pair.key]=pair.value;
return _115;
});
},toQueryString:function(){
return this.map(function(pair){
return pair.map(encodeURIComponent).join("=");
}).join("&");
},inspect:function(){
return "#<Hash:{"+this.map(function(pair){
return pair.map(Object.inspect).join(": ");
}).join(", ")+"}>";
}};
function $H(_119){
var hash=Object.extend({},_119||{});
Object.extend(hash,Enumerable);
Object.extend(hash,Hash);
return hash;
}
ObjectRange=Class.create();
Object.extend(ObjectRange.prototype,Enumerable);
Object.extend(ObjectRange.prototype,{initialize:function(_11b,end,_11d){
this.start=_11b;
this.end=end;
this.exclusive=_11d;
},_each:function(_11e){
var _11f=this.start;
do{
_11e(_11f);
_11f=_11f.succ();
}while(this.include(_11f));
},include:function(_120){
if(_120<this.start){
return false;
}
if(this.exclusive){
return _120<this.end;
}
return _120<=this.end;
}});
var $R=function(_121,end,_123){
return new ObjectRange(_121,end,_123);
};
var Ajax={getTransport:function(){
return Try.these(function(){
return new XMLHttpRequest();
},function(){
return new ActiveXObject("Msxml2.XMLHTTP");
},function(){
return new ActiveXObject("Microsoft.XMLHTTP");
})||false;
},activeRequestCount:0};
Ajax.Responders={responders:[],_each:function(_124){
this.responders._each(_124);
},register:function(_125){
if(!this.include(_125)){
this.responders.push(_125);
}
},unregister:function(_126){
this.responders=this.responders.without(_126);
},dispatch:function(_127,_128,_129,json){
this.each(function(_12b){
if(_12b[_127]&&typeof _12b[_127]=="function"){
try{
_12b[_127].apply(_12b,[_128,_129,json]);
}
catch(e){
}
}
});
}};
Object.extend(Ajax.Responders,Enumerable);
Ajax.Responders.register({onCreate:function(){
Ajax.activeRequestCount++;
},onComplete:function(){
Ajax.activeRequestCount--;
}});
Ajax.Base=function(){
};
Ajax.Base.prototype={setOptions:function(_12c){
this.options={method:"post",asynchronous:true,contentType:"application/x-www-form-urlencoded",parameters:""};
Object.extend(this.options,_12c||{});
},responseIsSuccess:function(){
return this.transport.status==undefined||this.transport.status==0||(this.transport.status>=200&&this.transport.status<300);
},responseIsFailure:function(){
return !this.responseIsSuccess();
}};
Ajax.Request=Class.create();
Ajax.Request.Events=["Uninitialized","Loading","Loaded","Interactive","Complete"];
Ajax.Request.prototype=Object.extend(new Ajax.Base(),{initialize:function(url,_12e){
this.transport=Ajax.getTransport();
this.setOptions(_12e);
this.request(url);
},request:function(url){
var _130=this.options.parameters||"";
if(_130.length>0){
_130+="&_=";
}
try{
this.url=url;
if(this.options.method=="get"&&_130.length>0){
this.url+=(this.url.match(/\?/)?"&":"?")+_130;
}
Ajax.Responders.dispatch("onCreate",this,this.transport);
this.transport.open(this.options.method,this.url,this.options.asynchronous);
if(this.options.asynchronous){
this.transport.onreadystatechange=this.onStateChange.bind(this);
setTimeout((function(){
this.respondToReadyState(1);
}).bind(this),10);
}
this.setRequestHeaders();
var body=this.options.postBody?this.options.postBody:_130;
this.transport.send(this.options.method=="post"?body:null);
}
catch(e){
this.dispatchException(e);
}
},setRequestHeaders:function(){
var _132=["X-Requested-With","XMLHttpRequest","X-Prototype-Version",Prototype.Version,"Accept","text/javascript, text/html, application/xml, text/xml, */*"];
if(this.options.method=="post"){
_132.push("Content-type",this.options.contentType);
if(this.transport.overrideMimeType){
_132.push("Connection","close");
}
}
if(this.options.requestHeaders){
_132.push.apply(_132,this.options.requestHeaders);
}
for(var i=0;i<_132.length;i+=2){
this.transport.setRequestHeader(_132[i],_132[i+1]);
}
},onStateChange:function(){
var _134=this.transport.readyState;
if(_134!=1){
this.respondToReadyState(this.transport.readyState);
}
},header:function(name){
try{
return this.transport.getResponseHeader(name);
}
catch(e){
}
},evalJSON:function(){
try{
return eval("("+this.header("X-JSON")+")");
}
catch(e){
}
},evalResponse:function(){
try{
return eval(this.transport.responseText);
}
catch(e){
this.dispatchException(e);
}
},respondToReadyState:function(_136){
var _137=Ajax.Request.Events[_136];
var _138=this.transport,json=this.evalJSON();
if(_137=="Complete"){
try{
(this.options["on"+this.transport.status]||this.options["on"+(this.responseIsSuccess()?"Success":"Failure")]||Prototype.emptyFunction)(_138,json);
}
catch(e){
this.dispatchException(e);
}
if((this.header("Content-type")||"").match(/^text\/javascript/i)){
this.evalResponse();
}
}
try{
(this.options["on"+_137]||Prototype.emptyFunction)(_138,json);
Ajax.Responders.dispatch("on"+_137,this,_138,json);
}
catch(e){
this.dispatchException(e);
}
if(_137=="Complete"){
this.transport.onreadystatechange=Prototype.emptyFunction;
}
},dispatchException:function(_13a){
(this.options.onException||Prototype.emptyFunction)(this,_13a);
Ajax.Responders.dispatch("onException",this,_13a);
}});
Ajax.Updater=Class.create();
Object.extend(Object.extend(Ajax.Updater.prototype,Ajax.Request.prototype),{initialize:function(_13b,url,_13d){
this.containers={success:_13b.success?$(_13b.success):$(_13b),failure:_13b.failure?$(_13b.failure):(_13b.success?null:$(_13b))};
this.transport=Ajax.getTransport();
this.setOptions(_13d);
var _13e=this.options.onComplete||Prototype.emptyFunction;
this.options.onComplete=(function(_13f,_140){
this.updateContent();
_13e(_13f,_140);
}).bind(this);
this.request(url);
},updateContent:function(){
var _141=this.responseIsSuccess()?this.containers.success:this.containers.failure;
var _142=this.transport.responseText;
if(!this.options.evalScripts){
_142=_142.stripScripts();
}
if(_141){
if(this.options.insertion){
new this.options.insertion(_141,_142);
}else{
Element.update(_141,_142);
}
}
if(this.responseIsSuccess()){
if(this.onComplete){
setTimeout(this.onComplete.bind(this),10);
}
}
}});
Ajax.PeriodicalUpdater=Class.create();
Ajax.PeriodicalUpdater.prototype=Object.extend(new Ajax.Base(),{initialize:function(_143,url,_145){
this.setOptions(_145);
this.onComplete=this.options.onComplete;
this.frequency=(this.options.frequency||2);
this.decay=(this.options.decay||1);
this.updater={};
this.container=_143;
this.url=url;
this.start();
},start:function(){
this.options.onComplete=this.updateComplete.bind(this);
this.onTimerEvent();
},stop:function(){
this.updater.onComplete=undefined;
clearTimeout(this.timer);
(this.onComplete||Prototype.emptyFunction).apply(this,arguments);
},updateComplete:function(_146){
if(this.options.decay){
this.decay=(_146.responseText==this.lastText?this.decay*this.options.decay:1);
this.lastText=_146.responseText;
}
this.timer=setTimeout(this.onTimerEvent.bind(this),this.decay*this.frequency*1000);
},onTimerEvent:function(){
this.updater=new Ajax.Updater(this.container,this.url,this.options);
}});
function $(){
var _147=[],_148;
for(var i=0;i<arguments.length;i++){
_148=arguments[i];
if(typeof _148=="string"){
_148=document.getElementById(_148);
}
_147.push(Element.extend(_148));
}
return _147.length<2?_147[0]:_147;
}
document.getElementsByClassName=function(_14a,_14b){
var _14c=($(_14b)||document.body).getElementsByTagName("*");
return $A(_14c).inject([],function(_14d,_14e){
if(_14e.className.match(new RegExp("(^|\\s)"+_14a+"(\\s|$)"))){
_14d.push(Element.extend(_14e));
}
return _14d;
});
};
if(!window.Element){
var Element=new Object();
}
Element.extend=function(_14f){
if(!_14f){
return;
}
if(_nativeExtensions){
return _14f;
}
if(!_14f._extended&&_14f.tagName&&_14f!=window){
var _150=Element.Methods,_151=Element.extend.cache;
for(property in _150){
var _152=_150[property];
if(typeof _152=="function"){
_14f[property]=_151.findOrStore(_152);
}
}
}
_14f._extended=true;
return _14f;
};
Element.extend.cache={findOrStore:function(_153){
return this[_153]=this[_153]||function(){
return _153.apply(null,[this].concat($A(arguments)));
};
}};
Element.Methods={visible:function(_154){
return $(_154).style.display!="none";
},toggle:function(){
for(var i=0;i<arguments.length;i++){
var _156=$(arguments[i]);
Element[Element.visible(_156)?"hide":"show"](_156);
}
},hide:function(){
for(var i=0;i<arguments.length;i++){
var _158=$(arguments[i]);
_158.style.display="none";
}
},show:function(){
for(var i=0;i<arguments.length;i++){
var _15a=$(arguments[i]);
_15a.style.display="";
}
},remove:function(_15b){
_15b=$(_15b);
_15b.parentNode.removeChild(_15b);
},update:function(_15c,html){
$(_15c).innerHTML=html.stripScripts();
setTimeout(function(){
html.evalScripts();
},10);
},replace:function(_15e,html){
_15e=$(_15e);
if(_15e.outerHTML){
_15e.outerHTML=html.stripScripts();
}else{
var _160=_15e.ownerDocument.createRange();
_160.selectNodeContents(_15e);
_15e.parentNode.replaceChild(_160.createContextualFragment(html.stripScripts()),_15e);
}
setTimeout(function(){
html.evalScripts();
},10);
},getHeight:function(_161){
_161=$(_161);
return _161.offsetHeight;
},classNames:function(_162){
return new Element.ClassNames(_162);
},hasClassName:function(_163,_164){
if(!(_163=$(_163))){
return;
}
return Element.classNames(_163).include(_164);
},addClassName:function(_165,_166){
if(!(_165=$(_165))){
return;
}
return Element.classNames(_165).add(_166);
},removeClassName:function(_167,_168){
if(!(_167=$(_167))){
return;
}
return Element.classNames(_167).remove(_168);
},cleanWhitespace:function(_169){
_169=$(_169);
for(var i=0;i<_169.childNodes.length;i++){
var node=_169.childNodes[i];
if(node.nodeType==3&&!/\S/.test(node.nodeValue)){
Element.remove(node);
}
}
},empty:function(_16c){
return $(_16c).innerHTML.match(/^\s*$/);
},childOf:function(_16d,_16e){
_16d=$(_16d),_16e=$(_16e);
while(_16d=_16d.parentNode){
if(_16d==_16e){
return true;
}
}
return false;
},scrollTo:function(_16f){
_16f=$(_16f);
var x=_16f.x?_16f.x:_16f.offsetLeft,y=_16f.y?_16f.y:_16f.offsetTop;
window.scrollTo(x,y);
},getStyle:function(_172,_173){
_172=$(_172);
var _174=_172.style[_173.camelize()];
if(!_174){
if(document.defaultView&&document.defaultView.getComputedStyle){
var css=document.defaultView.getComputedStyle(_172,null);
_174=css?css.getPropertyValue(_173):null;
}else{
if(_172.currentStyle){
_174=_172.currentStyle[_173.camelize()];
}
}
}
if(window.opera&&["left","top","right","bottom"].include(_173)){
if(Element.getStyle(_172,"position")=="static"){
_174="auto";
}
}
return _174=="auto"?null:_174;
},setStyle:function(_176,_177){
_176=$(_176);
for(var name in _177){
_176.style[name.camelize()]=_177[name];
}
},getDimensions:function(_179){
_179=$(_179);
if(Element.getStyle(_179,"display")!="none"){
return {width:_179.offsetWidth,height:_179.offsetHeight};
}
var els=_179.style;
var _17b=els.visibility;
var _17c=els.position;
els.visibility="hidden";
els.position="absolute";
els.display="";
var _17d=_179.clientWidth;
var _17e=_179.clientHeight;
els.display="none";
els.position=_17c;
els.visibility=_17b;
return {width:_17d,height:_17e};
},makePositioned:function(_17f){
_17f=$(_17f);
var pos=Element.getStyle(_17f,"position");
if(pos=="static"||!pos){
_17f._madePositioned=true;
_17f.style.position="relative";
if(window.opera){
_17f.style.top=0;
_17f.style.left=0;
}
}
},undoPositioned:function(_181){
_181=$(_181);
if(_181._madePositioned){
_181._madePositioned=undefined;
_181.style.position=_181.style.top=_181.style.left=_181.style.bottom=_181.style.right="";
}
},makeClipping:function(_182){
_182=$(_182);
if(_182._overflow){
return;
}
_182._overflow=_182.style.overflow;
if((Element.getStyle(_182,"overflow")||"visible")!="hidden"){
_182.style.overflow="hidden";
}
},undoClipping:function(_183){
_183=$(_183);
if(_183._overflow){
return;
}
_183.style.overflow=_183._overflow;
_183._overflow=undefined;
}};
Object.extend(Element,Element.Methods);
var _nativeExtensions=false;
if(!HTMLElement&&/Konqueror|Safari|KHTML/.test(navigator.userAgent)){
var HTMLElement={};
HTMLElement.prototype=document.createElement("div").__proto__;
}
Element.addMethods=function(_184){
Object.extend(Element.Methods,_184||{});
if(typeof HTMLElement!="undefined"){
var _184=Element.Methods,_185=Element.extend.cache;
for(property in _184){
var _186=_184[property];
if(typeof _186=="function"){
HTMLElement.prototype[property]=_185.findOrStore(_186);
}
}
_nativeExtensions=true;
}
};
Element.addMethods();
var Toggle=new Object();
Toggle.display=Element.toggle;
Abstract.Insertion=function(_187){
this.adjacency=_187;
};
Abstract.Insertion.prototype={initialize:function(_188,_189){
this.element=$(_188);
this.content=_189.stripScripts();
if(this.adjacency&&this.element.insertAdjacentHTML){
try{
this.element.insertAdjacentHTML(this.adjacency,this.content);
}
catch(e){
var _18a=this.element.tagName.toLowerCase();
if(_18a=="tbody"||_18a=="tr"){
this.insertContent(this.contentFromAnonymousTable());
}else{
throw e;
}
}
}else{
this.range=this.element.ownerDocument.createRange();
if(this.initializeRange){
this.initializeRange();
}
this.insertContent([this.range.createContextualFragment(this.content)]);
}
setTimeout(function(){
_189.evalScripts();
},10);
},contentFromAnonymousTable:function(){
var div=document.createElement("div");
div.innerHTML="<table><tbody>"+this.content+"</tbody></table>";
return $A(div.childNodes[0].childNodes[0].childNodes);
}};
var Insertion=new Object();
Insertion.Before=Class.create();
Insertion.Before.prototype=Object.extend(new Abstract.Insertion("beforeBegin"),{initializeRange:function(){
this.range.setStartBefore(this.element);
},insertContent:function(_18c){
_18c.each((function(_18d){
this.element.parentNode.insertBefore(_18d,this.element);
}).bind(this));
}});
Insertion.Top=Class.create();
Insertion.Top.prototype=Object.extend(new Abstract.Insertion("afterBegin"),{initializeRange:function(){
this.range.selectNodeContents(this.element);
this.range.collapse(true);
},insertContent:function(_18e){
_18e.reverse(false).each((function(_18f){
this.element.insertBefore(_18f,this.element.firstChild);
}).bind(this));
}});
Insertion.Bottom=Class.create();
Insertion.Bottom.prototype=Object.extend(new Abstract.Insertion("beforeEnd"),{initializeRange:function(){
this.range.selectNodeContents(this.element);
this.range.collapse(this.element);
},insertContent:function(_190){
_190.each((function(_191){
this.element.appendChild(_191);
}).bind(this));
}});
Insertion.After=Class.create();
Insertion.After.prototype=Object.extend(new Abstract.Insertion("afterEnd"),{initializeRange:function(){
this.range.setStartAfter(this.element);
},insertContent:function(_192){
_192.each((function(_193){
this.element.parentNode.insertBefore(_193,this.element.nextSibling);
}).bind(this));
}});
Element.ClassNames=Class.create();
Element.ClassNames.prototype={initialize:function(_194){
this.element=$(_194);
},_each:function(_195){
this.element.className.split(/\s+/).select(function(name){
return name.length>0;
})._each(_195);
},set:function(_197){
this.element.className=_197;
},add:function(_198){
if(this.include(_198)){
return;
}
this.set(this.toArray().concat(_198).join(" "));
},remove:function(_199){
if(!this.include(_199)){
return;
}
this.set(this.select(function(_19a){
return _19a!=_199;
}).join(" "));
},toString:function(){
return this.toArray().join(" ");
}};
Object.extend(Element.ClassNames.prototype,Enumerable);
var Selector=Class.create();
Selector.prototype={initialize:function(_19b){
this.params={classNames:[]};
this.expression=_19b.toString().strip();
this.parseExpression();
this.compileMatcher();
},parseExpression:function(){
function abort(_19c){
throw "Parse error in selector: "+_19c;
}
if(this.expression==""){
abort("empty expression");
}
var _19d=this.params,expr=this.expression,_19f,_1a0,_1a1,rest;
while(_19f=expr.match(/^(.*)\[([a-z0-9_:-]+?)(?:([~\|!]?=)(?:"([^"]*)"|([^\]\s]*)))?\]$/i)){
_19d.attributes=_19d.attributes||[];
_19d.attributes.push({name:_19f[2],operator:_19f[3],value:_19f[4]||_19f[5]||""});
expr=_19f[1];
}
if(expr=="*"){
return this.params.wildcard=true;
}
while(_19f=expr.match(/^([^a-z0-9_-])?([a-z0-9_-]+)(.*)/i)){
_1a0=_19f[1],_1a1=_19f[2],rest=_19f[3];
switch(_1a0){
case "#":
_19d.id=_1a1;
break;
case ".":
_19d.classNames.push(_1a1);
break;
case "":
case undefined:
_19d.tagName=_1a1.toUpperCase();
break;
default:
abort(expr.inspect());
}
expr=rest;
}
if(expr.length>0){
abort(expr.inspect());
}
},buildMatchExpression:function(){
var _1a3=this.params,_1a4=[],_1a5;
if(_1a3.wildcard){
_1a4.push("true");
}
if(_1a5=_1a3.id){
_1a4.push("element.id == "+_1a5.inspect());
}
if(_1a5=_1a3.tagName){
_1a4.push("element.tagName.toUpperCase() == "+_1a5.inspect());
}
if((_1a5=_1a3.classNames).length>0){
for(var i=0;i<_1a5.length;i++){
_1a4.push("Element.hasClassName(element, "+_1a5[i].inspect()+")");
}
}
if(_1a5=_1a3.attributes){
_1a5.each(function(_1a7){
var _1a8="element.getAttribute("+_1a7.name.inspect()+")";
var _1a9=function(_1aa){
return _1a8+" && "+_1a8+".split("+_1aa.inspect()+")";
};
switch(_1a7.operator){
case "=":
_1a4.push(_1a8+" == "+_1a7.value.inspect());
break;
case "~=":
_1a4.push(_1a9(" ")+".include("+_1a7.value.inspect()+")");
break;
case "|=":
_1a4.push(_1a9("-")+".first().toUpperCase() == "+_1a7.value.toUpperCase().inspect());
break;
case "!=":
_1a4.push(_1a8+" != "+_1a7.value.inspect());
break;
case "":
case undefined:
_1a4.push(_1a8+" != null");
break;
default:
throw "Unknown operator "+_1a7.operator+" in selector";
}
});
}
return _1a4.join(" && ");
},compileMatcher:function(){
this.match=new Function("element","if (!element.tagName) return false;       return "+this.buildMatchExpression());
},findElements:function(_1ab){
var _1ac;
if(_1ac=$(this.params.id)){
if(this.match(_1ac)){
if(!_1ab||Element.childOf(_1ac,_1ab)){
return [_1ac];
}
}
}
_1ab=(_1ab||document).getElementsByTagName(this.params.tagName||"*");
var _1ad=[];
for(var i=0;i<_1ab.length;i++){
if(this.match(_1ac=_1ab[i])){
_1ad.push(Element.extend(_1ac));
}
}
return _1ad;
},toString:function(){
return this.expression;
}};
function $$(){
return $A(arguments).map(function(_1af){
return _1af.strip().split(/\s+/).inject([null],function(_1b0,expr){
var _1b2=new Selector(expr);
return _1b0.map(_1b2.findElements.bind(_1b2)).flatten();
});
}).flatten();
}
var Field={clear:function(){
for(var i=0;i<arguments.length;i++){
$(arguments[i]).value="";
}
},focus:function(_1b4){
$(_1b4).focus();
},present:function(){
for(var i=0;i<arguments.length;i++){
if($(arguments[i]).value==""){
return false;
}
}
return true;
},select:function(_1b6){
$(_1b6).select();
},activate:function(_1b7){
_1b7=$(_1b7);
_1b7.focus();
if(_1b7.select){
_1b7.select();
}
}};
var Form={serialize:function(form){
var _1b9=Form.getElements($(form));
var _1ba=new Array();
for(var i=0;i<_1b9.length;i++){
var _1bc=Form.Element.serialize(_1b9[i]);
if(_1bc){
_1ba.push(_1bc);
}
}
return _1ba.join("&");
},getElements:function(form){
form=$(form);
var _1be=new Array();
for(var _1bf in Form.Element.Serializers){
var _1c0=form.getElementsByTagName(_1bf);
for(var j=0;j<_1c0.length;j++){
_1be.push(_1c0[j]);
}
}
return _1be;
},getInputs:function(form,_1c3,name){
form=$(form);
var _1c5=form.getElementsByTagName("input");
if(!_1c3&&!name){
return _1c5;
}
var _1c6=new Array();
for(var i=0;i<_1c5.length;i++){
var _1c8=_1c5[i];
if((_1c3&&_1c8.type!=_1c3)||(name&&_1c8.name!=name)){
continue;
}
_1c6.push(_1c8);
}
return _1c6;
},disable:function(form){
var _1ca=Form.getElements(form);
for(var i=0;i<_1ca.length;i++){
var _1cc=_1ca[i];
_1cc.blur();
_1cc.disabled="true";
}
},enable:function(form){
var _1ce=Form.getElements(form);
for(var i=0;i<_1ce.length;i++){
var _1d0=_1ce[i];
_1d0.disabled="";
}
},findFirstElement:function(form){
return Form.getElements(form).find(function(_1d2){
return _1d2.type!="hidden"&&!_1d2.disabled&&["input","select","textarea"].include(_1d2.tagName.toLowerCase());
});
},focusFirstElement:function(form){
Field.activate(Form.findFirstElement(form));
},reset:function(form){
$(form).reset();
}};
Form.Element={serialize:function(_1d5){
_1d5=$(_1d5);
var _1d6=_1d5.tagName.toLowerCase();
var _1d7=Form.Element.Serializers[_1d6](_1d5);
if(_1d7){
var key=encodeURIComponent(_1d7[0]);
if(key.length==0){
return;
}
if(_1d7[1].constructor!=Array){
_1d7[1]=[_1d7[1]];
}
return _1d7[1].map(function(_1d9){
return key+"="+encodeURIComponent(_1d9);
}).join("&");
}
},getValue:function(_1da){
_1da=$(_1da);
var _1db=_1da.tagName.toLowerCase();
var _1dc=Form.Element.Serializers[_1db](_1da);
if(_1dc){
return _1dc[1];
}
}};
Form.Element.Serializers={input:function(_1dd){
switch(_1dd.type.toLowerCase()){
case "submit":
case "hidden":
case "password":
case "text":
return Form.Element.Serializers.textarea(_1dd);
case "checkbox":
case "radio":
return Form.Element.Serializers.inputSelector(_1dd);
}
return false;
},inputSelector:function(_1de){
if(_1de.checked){
return [_1de.name,_1de.value];
}
},textarea:function(_1df){
return [_1df.name,_1df.value];
},select:function(_1e0){
return Form.Element.Serializers[_1e0.type=="select-one"?"selectOne":"selectMany"](_1e0);
},selectOne:function(_1e1){
var _1e2="",opt,_1e4=_1e1.selectedIndex;
if(_1e4>=0){
opt=_1e1.options[_1e4];
_1e2=opt.value||opt.text;
}
return [_1e1.name,_1e2];
},selectMany:function(_1e5){
var _1e6=[];
for(var i=0;i<_1e5.length;i++){
var opt=_1e5.options[i];
if(opt.selected){
_1e6.push(opt.value||opt.text);
}
}
return [_1e5.name,_1e6];
}};
var $F=Form.Element.getValue;
Abstract.TimedObserver=function(){
};
Abstract.TimedObserver.prototype={initialize:function(_1e9,_1ea,_1eb){
this.frequency=_1ea;
this.element=$(_1e9);
this.callback=_1eb;
this.lastValue=this.getValue();
this.registerCallback();
},registerCallback:function(){
setInterval(this.onTimerEvent.bind(this),this.frequency*1000);
},onTimerEvent:function(){
var _1ec=this.getValue();
if(this.lastValue!=_1ec){
this.callback(this.element,_1ec);
this.lastValue=_1ec;
}
}};
Form.Element.Observer=Class.create();
Form.Element.Observer.prototype=Object.extend(new Abstract.TimedObserver(),{getValue:function(){
return Form.Element.getValue(this.element);
}});
Form.Observer=Class.create();
Form.Observer.prototype=Object.extend(new Abstract.TimedObserver(),{getValue:function(){
return Form.serialize(this.element);
}});
Abstract.EventObserver=function(){
};
Abstract.EventObserver.prototype={initialize:function(_1ed,_1ee){
this.element=$(_1ed);
this.callback=_1ee;
this.lastValue=this.getValue();
if(this.element.tagName.toLowerCase()=="form"){
this.registerFormCallbacks();
}else{
this.registerCallback(this.element);
}
},onElementEvent:function(){
var _1ef=this.getValue();
if(this.lastValue!=_1ef){
this.callback(this.element,_1ef);
this.lastValue=_1ef;
}
},registerFormCallbacks:function(){
var _1f0=Form.getElements(this.element);
for(var i=0;i<_1f0.length;i++){
this.registerCallback(_1f0[i]);
}
},registerCallback:function(_1f2){
if(_1f2.type){
switch(_1f2.type.toLowerCase()){
case "checkbox":
case "radio":
Event.observe(_1f2,"click",this.onElementEvent.bind(this));
break;
case "password":
case "text":
case "textarea":
case "select-one":
case "select-multiple":
Event.observe(_1f2,"change",this.onElementEvent.bind(this));
break;
}
}
}};
Form.Element.EventObserver=Class.create();
Form.Element.EventObserver.prototype=Object.extend(new Abstract.EventObserver(),{getValue:function(){
return Form.Element.getValue(this.element);
}});
Form.EventObserver=Class.create();
Form.EventObserver.prototype=Object.extend(new Abstract.EventObserver(),{getValue:function(){
return Form.serialize(this.element);
}});
if(!window.Event){
var Event=new Object();
}
Object.extend(Event,{KEY_BACKSPACE:8,KEY_TAB:9,KEY_RETURN:13,KEY_ESC:27,KEY_LEFT:37,KEY_UP:38,KEY_RIGHT:39,KEY_DOWN:40,KEY_DELETE:46,element:function(_1f3){
return _1f3.target||_1f3.srcElement;
},isLeftClick:function(_1f4){
return (((_1f4.which)&&(_1f4.which==1))||((_1f4.button)&&(_1f4.button==1)));
},pointerX:function(_1f5){
return _1f5.pageX||(_1f5.clientX+(document.documentElement.scrollLeft||document.body.scrollLeft));
},pointerY:function(_1f6){
return _1f6.pageY||(_1f6.clientY+(document.documentElement.scrollTop||document.body.scrollTop));
},stop:function(_1f7){
if(_1f7.preventDefault){
_1f7.preventDefault();
_1f7.stopPropagation();
}else{
_1f7.returnValue=false;
_1f7.cancelBubble=true;
}
},findElement:function(_1f8,_1f9){
var _1fa=Event.element(_1f8);
while(_1fa.parentNode&&(!_1fa.tagName||(_1fa.tagName.toUpperCase()!=_1f9.toUpperCase()))){
_1fa=_1fa.parentNode;
}
return _1fa;
},observers:false,_observeAndCache:function(_1fb,name,_1fd,_1fe){
if(!this.observers){
this.observers=[];
}
if(_1fb.addEventListener){
this.observers.push([_1fb,name,_1fd,_1fe]);
_1fb.addEventListener(name,_1fd,_1fe);
}else{
if(_1fb.attachEvent){
this.observers.push([_1fb,name,_1fd,_1fe]);
_1fb.attachEvent("on"+name,_1fd);
}
}
},unloadCache:function(){
if(!Event.observers){
return;
}
for(var i=0;i<Event.observers.length;i++){
Event.stopObserving.apply(this,Event.observers[i]);
Event.observers[i][0]=null;
}
Event.observers=false;
},observe:function(_200,name,_202,_203){
var _200=$(_200);
_203=_203||false;
if(name=="keypress"&&(navigator.appVersion.match(/Konqueror|Safari|KHTML/)||_200.attachEvent)){
name="keydown";
}
this._observeAndCache(_200,name,_202,_203);
},stopObserving:function(_204,name,_206,_207){
var _204=$(_204);
_207=_207||false;
if(name=="keypress"&&(navigator.appVersion.match(/Konqueror|Safari|KHTML/)||_204.detachEvent)){
name="keydown";
}
if(_204.removeEventListener){
_204.removeEventListener(name,_206,_207);
}else{
if(_204.detachEvent){
_204.detachEvent("on"+name,_206);
}
}
}});
if(navigator.appVersion.match(/\bMSIE\b/)){
Event.observe(window,"unload",Event.unloadCache,false);
}
var Position={includeScrollOffsets:false,prepare:function(){
this.deltaX=window.pageXOffset||document.documentElement.scrollLeft||document.body.scrollLeft||0;
this.deltaY=window.pageYOffset||document.documentElement.scrollTop||document.body.scrollTop||0;
},realOffset:function(_208){
var _209=0,_20a=0;
do{
_209+=_208.scrollTop||0;
_20a+=_208.scrollLeft||0;
_208=_208.parentNode;
}while(_208);
return [_20a,_209];
},cumulativeOffset:function(_20b){
var _20c=0,_20d=0;
do{
_20c+=_20b.offsetTop||0;
_20d+=_20b.offsetLeft||0;
_20b=_20b.offsetParent;
}while(_20b);
return [_20d,_20c];
},positionedOffset:function(_20e){
var _20f=0,_210=0;
do{
_20f+=_20e.offsetTop||0;
_210+=_20e.offsetLeft||0;
_20e=_20e.offsetParent;
if(_20e){
p=Element.getStyle(_20e,"position");
if(p=="relative"||p=="absolute"){
break;
}
}
}while(_20e);
return [_210,_20f];
},offsetParent:function(_211){
if(_211.offsetParent){
return _211.offsetParent;
}
if(_211==document.body){
return _211;
}
while((_211=_211.parentNode)&&_211!=document.body){
if(Element.getStyle(_211,"position")!="static"){
return _211;
}
}
return document.body;
},within:function(_212,x,y){
if(this.includeScrollOffsets){
return this.withinIncludingScrolloffsets(_212,x,y);
}
this.xcomp=x;
this.ycomp=y;
this.offset=this.cumulativeOffset(_212);
return (y>=this.offset[1]&&y<this.offset[1]+_212.offsetHeight&&x>=this.offset[0]&&x<this.offset[0]+_212.offsetWidth);
},withinIncludingScrolloffsets:function(_215,x,y){
var _218=this.realOffset(_215);
this.xcomp=x+_218[0]-this.deltaX;
this.ycomp=y+_218[1]-this.deltaY;
this.offset=this.cumulativeOffset(_215);
return (this.ycomp>=this.offset[1]&&this.ycomp<this.offset[1]+_215.offsetHeight&&this.xcomp>=this.offset[0]&&this.xcomp<this.offset[0]+_215.offsetWidth);
},overlap:function(mode,_21a){
if(!mode){
return 0;
}
if(mode=="vertical"){
return ((this.offset[1]+_21a.offsetHeight)-this.ycomp)/_21a.offsetHeight;
}
if(mode=="horizontal"){
return ((this.offset[0]+_21a.offsetWidth)-this.xcomp)/_21a.offsetWidth;
}
},clone:function(_21b,_21c){
_21b=$(_21b);
_21c=$(_21c);
_21c.style.position="absolute";
var _21d=this.cumulativeOffset(_21b);
_21c.style.top=_21d[1]+"px";
_21c.style.left=_21d[0]+"px";
_21c.style.width=_21b.offsetWidth+"px";
_21c.style.height=_21b.offsetHeight+"px";
},page:function(_21e){
var _21f=0,_220=0;
var _221=_21e;
do{
_21f+=_221.offsetTop||0;
_220+=_221.offsetLeft||0;
if(_221.offsetParent==document.body){
if(Element.getStyle(_221,"position")=="absolute"){
break;
}
}
}while(_221=_221.offsetParent);
_221=_21e;
do{
_21f-=_221.scrollTop||0;
_220-=_221.scrollLeft||0;
}while(_221=_221.parentNode);
return [_220,_21f];
},clone:function(_222,_223){
var _224=Object.extend({setLeft:true,setTop:true,setWidth:true,setHeight:true,offsetTop:0,offsetLeft:0},arguments[2]||{});
_222=$(_222);
var p=Position.page(_222);
_223=$(_223);
var _226=[0,0];
var _227=null;
if(Element.getStyle(_223,"position")=="absolute"){
_227=Position.offsetParent(_223);
_226=Position.page(_227);
}
if(_227==document.body){
_226[0]-=document.body.offsetLeft;
_226[1]-=document.body.offsetTop;
}
if(_224.setLeft){
_223.style.left=(p[0]-_226[0]+_224.offsetLeft)+"px";
}
if(_224.setTop){
_223.style.top=(p[1]-_226[1]+_224.offsetTop)+"px";
}
if(_224.setWidth){
_223.style.width=_222.offsetWidth+"px";
}
if(_224.setHeight){
_223.style.height=_222.offsetHeight+"px";
}
},absolutize:function(_228){
_228=$(_228);
if(_228.style.position=="absolute"){
return;
}
Position.prepare();
var _229=Position.positionedOffset(_228);
var top=_229[1];
var left=_229[0];
var _22c=_228.clientWidth;
var _22d=_228.clientHeight;
_228._originalLeft=left-parseFloat(_228.style.left||0);
_228._originalTop=top-parseFloat(_228.style.top||0);
_228._originalWidth=_228.style.width;
_228._originalHeight=_228.style.height;
_228.style.position="absolute";
_228.style.top=top+"px";
_228.style.left=left+"px";
_228.style.width=_22c+"px";
_228.style.height=_22d+"px";
},relativize:function(_22e){
_22e=$(_22e);
if(_22e.style.position=="relative"){
return;
}
Position.prepare();
_22e.style.position="relative";
var top=parseFloat(_22e.style.top||0)-(_22e._originalTop||0);
var left=parseFloat(_22e.style.left||0)-(_22e._originalLeft||0);
_22e.style.top=top+"px";
_22e.style.left=left+"px";
_22e.style.height=_22e._originalHeight;
_22e.style.width=_22e._originalWidth;
}};
if(/Konqueror|Safari|KHTML/.test(navigator.userAgent)){
Position.cumulativeOffset=function(_231){
var _232=0,_233=0;
do{
_232+=_231.offsetTop||0;
_233+=_231.offsetLeft||0;
if(_231.offsetParent==document.body){
if(Element.getStyle(_231,"position")=="absolute"){
break;
}
}
_231=_231.offsetParent;
}while(_231);
return [_233,_232];
};
}

