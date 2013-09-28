var grayscale=function(){var q="color,backgroundColor,borderBottomColor,borderTopColor,borderLeftColor,borderRightColor,backgroundImage".split(","),n={init:function(a,c){if(a.nodeName.toLowerCase()!=="img")h(a).backgroundImageSRC=c,a.style.backgroundImage=""},reset:function(a){if(a.nodeName.toLowerCase()!=="img")a.style.backgroundImage="url("+(h(a).backgroundImageSRC||"")+")"}},p=function(a){return RegExp("https?://(?!"+window.location.hostname+")").test(a)},h=function(){var a=[0],c="data"+ +new Date;
return function(e){var b=e[c],d=a.length;b||(b=e[c]=d,a[b]={});return a[b]}}(),m=function(a,c,e){var b=document.createElement("canvas"),d=b.getContext("2d"),j=a.naturalHeight||a.offsetHeight||a.height,i=a.naturalWidth||a.offsetWidth||a.width,f;b.height=j;b.width=i;d.drawImage(a,0,0);try{f=d.getImageData(0,0,i,j)}catch(g){}if(c){m.preparing=!0;var l=0;(function(){if(m.preparing){if(l===j)d.putImageData(f,0,0,0,0,i,j),e?h(e).BGdataURL=b.toDataURL():h(a).dataURL=b.toDataURL();for(var c=0;c<i;c++){var g=
(l*i+c)*4;f.data[g]=f.data[g+1]=f.data[g+2]=r(f.data[g],f.data[g+1],f.data[g+2])}l++;setTimeout(arguments.callee,0)}})()}else{m.preparing=!1;for(l=0;l<j;l++)for(c=0;c<i;c++){var k=(l*i+c)*4;f.data[k]=f.data[k+1]=f.data[k+2]=r(f.data[k],f.data[k+1],f.data[k+2])}d.putImageData(f,0,0,0,0,i,j);return b}},t=function(a,c){var e=document.defaultView&&document.defaultView.getComputedStyle?document.defaultView.getComputedStyle(a,null)[c]:a.currentStyle[c];e&&/^#[A-F0-9]/i.test(e)&&(e=e.match(/[A-F0-9]{2}/ig),
e="rgb("+parseInt(e[0],16)+","+parseInt(e[1],16)+","+parseInt(e[2],16)+")");return e},r=function(a,c,e){return parseInt(0.2125*a+0.7154*c+0.0721*e,10)},s=function(a){var c=Array.prototype.slice.call(a.getElementsByTagName("*"));c.unshift(a);return c},o=function(a){if(a&&a[0]&&a.length&&a[0].nodeName)for(var a=Array.prototype.slice.call(a),c=-1,e=a.length;++c<e;)o.call(this,a[c]);else if(a=a||document.documentElement,document.createElement("canvas").getContext){a=s(a);c=-1;for(e=a.length;++c<e;){var b=
a[c];if(b.nodeName.toLowerCase()==="img"){var d=b.getAttribute("src");if(d)if(p(d))n.init(b,d);else{h(b).realSRC=d;try{b.src=h(b).dataURL||m(b).toDataURL()}catch(j){n.init(b,d)}}}else for(var d=0,i=q.length;d<i;d++){var f=q[d],g=t(b,f);if(g)if(b.style[f]&&(h(b)[f]=g),g.substring(0,4)==="rgb(")g=r.apply(null,g.match(/\d+/g)),b.style[f]=g="rgb("+g+","+g+","+g+")";else if(g.indexOf("url(")>-1){var l=/\(['"]?(.+?)['"]?\)/,k=g.match(l)[1];if(p(k))n.init(b,k),h(b).externalBG=!0;else try{var u=h(b).BGdataURL||
function(){var a=document.createElement("img");a.src=k;return m(a).toDataURL()}();b.style[f]=g.replace(l,function(){return"("+u+")"})}catch(v){n.init(b,k)}}}}}else a.style.filter="progid:DXImageTransform.Microsoft.BasicImage(grayscale=1)",a.style.zoom=1};o.reset=function(a){if(a&&a[0]&&a.length&&a[0].nodeName)for(var a=Array.prototype.slice.call(a),c=-1,e=a.length;++c<e;)o.reset.call(this,a[c]);else if(a=a||document.documentElement,document.createElement("canvas").getContext){a=s(a);c=-1;for(e=a.length;++c<
e;){var b=a[c];if(b.nodeName.toLowerCase()==="img"){var d=b.getAttribute("src");p(d)&&n.reset(b,d);b.src=h(b).realSRC||d}else for(var d=0,j=q.length;d<j;d++){h(b).externalBG&&n.reset(b);var i=q[d];b.style[i]=h(b)[i]||""}}}else a.style.filter="progid:DXImageTransform.Microsoft.BasicImage(grayscale=0)"};o.prepare=function(a){if(a&&a[0]&&a.length&&a[0].nodeName)for(var a=Array.prototype.slice.call(a),c=-1,e=a.length;++c<e;)o.prepare.call(null,a[c]);else if(a=a||document.documentElement,document.createElement("canvas").getContext){a=
s(a);c=-1;for(e=a.length;++c<e;){var b=a[c];if(h(b).skip)break;if(b.nodeName.toLowerCase()==="img")b.getAttribute("src")&&!p(b.src)&&m(b,!0);else{var d=t(b,"backgroundImage");if(d.indexOf("url(")>-1&&(d=d.match(/\(['"]?(.+?)['"]?\)/)[1],!p(d))){var j=document.createElement("img");j.src=d;m(j,!0,b)}}}}};return o}();
