"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[3465],{98631:function(e,t,r){var o=r(79619),n=r(1055),a=r(47051),i=r(15925),c=r(22488);function s(e,t,r,o,n,a){void 0!==n?a=void 0!==a?a:0:(n=[],a=0);let i=t;for(;i<r;){const t=e[i++];n[a++]=e[i++],n[a++]=t;for(let t=2;t<o;++t)n[a++]=e[i++]}return n.length=a,n}var u=r(81625),l=r(75531),m=r(2083),f=r(15113);class g extends c.Z{constructor(e){super(),e=e||{},this.dataProjection=(0,u.U2)("EPSG:4326"),this.factor_=e.factor?e.factor:1e5,this.geometryLayout_=e.geometryLayout?e.geometryLayout:"XY"}readFeatureFromText(e,t){const r=this.readGeometryFromText(e,t);return new o.Z(r)}readFeaturesFromText(e,t){return[this.readFeatureFromText(e,t)]}readGeometryFromText(e,t){const r=(0,l.tP)(this.geometryLayout_),o=function(e,t,r){let o;r=r||1e5;const n=new Array(t);for(o=0;o<t;++o)n[o]=0;const a=function(e,t){t=t||1e5;const r=function(e){const t=function(e){const t=[];let r=0,o=0;for(let n=0,a=e.length;n<a;++n){const a=e.charCodeAt(n)-63;r|=(31&a)<<o,a<32?(t.push(r),r=0,o=0):o+=5}return t}(e);for(let e=0,r=t.length;e<r;++e){const r=t[e];t[e]=1&r?~(r>>1):r>>1}return t}(e);for(let e=0,o=r.length;e<o;++e)r[e]/=t;return r}(e,r);for(let e=0,r=a.length;e<r;)for(o=0;o<t;++o,++e)n[o]+=a[e],a[e]=n[o];return a}(e,r,this.factor_);s(o,0,o.length,r,o);const n=(0,m.Ml)(o,0,o.length,r),a=new i.Z(n,this.geometryLayout_);return(0,f.fI)(a,!1,this.adaptOptions(t))}writeFeatureText(e,t){const r=e.getGeometry();if(r)return this.writeGeometryText(r,t);throw new Error("Expected `feature` to have a geometry")}writeFeaturesText(e,t){return this.writeFeatureText(e[0],t)}writeGeometryText(e,t){const r=(e=(0,f.fI)(e,!0,this.adaptOptions(t))).getFlatCoordinates(),o=e.getStride();return s(r,0,r.length,o,r),function(e,t,r){let o;r=r||1e5;const n=new Array(t);for(o=0;o<t;++o)n[o]=0;for(let r=0,a=e.length;r<a;)for(o=0;o<t;++o,++r){const t=e[r],a=t-n[o];n[o]=t,e[r]=a}return function(e,t){t=t||1e5;for(let r=0,o=e.length;r<o;++r)e[r]=Math.round(e[r]*t);return function(e){for(let t=0,r=e.length;t<r;++t){const r=e[t];e[t]=r<0?~(r<<1):r<<1}return function(e){let t="";for(let r=0,o=e.length;r<o;++r)t+=h(e[r]);return t}(e)}(e)}(e,r)}(r,o,this.factor_)}}function h(e){let t,r="";for(;e>=32;)t=63+(32|31&e),r+=String.fromCharCode(t),e>>=5;return t=e+63,r+=String.fromCharCode(t),r}var y=g,w=r(4711),d=r(40824),p=r(64469),Z=r(80677),x=r(82776),k=r(91652),C=r(64688),G=r(5002),F=r(72893),T=r(1733),S=r(91027);const P=new n.Z({target:document.getElementById("map"),view:new d.ZP({center:[-5639523.95,-3501274.52],zoom:10,minZoom:2,maxZoom:19}),layers:[new F.Z({source:new p.Z({attributions:'<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>',url:"https://api.maptiler.com/maps/hybrid/{z}/{x}/{y}.jpg?key=get_your_own_D6rA4zTHduk6KOKTXzGB",tileSize:512})})]});fetch("data/polyline/route.json").then((function(e){e.json().then((function(e){const t=e.routes[0].geometry,r=new y({factor:1e6}).readGeometry(t,{dataProjection:"EPSG:4326",featureProjection:"EPSG:3857"}),n=new o.Z({type:"route",geometry:r}),i=new o.Z({type:"icon",geometry:new a.Z(r.getFirstCoordinate())}),c=new o.Z({type:"icon",geometry:new a.Z(r.getLastCoordinate())}),s=i.getGeometry().clone(),u=new o.Z({type:"geoMarker",geometry:s}),l={route:new Z.ZP({stroke:new x.Z({width:6,color:[237,212,0,.8]})}),icon:new Z.ZP({image:new k.Z({anchor:[.5,1],src:"data/icon.png"})}),geoMarker:new Z.ZP({image:new C.Z({radius:7,fill:new G.Z({color:"black"}),stroke:new x.Z({color:"white",width:2})})})},m=new T.Z({source:new w.Z({features:[n,u,i,c]}),style:function(e){return l[e.get("type")]}});P.addLayer(m);const f=document.getElementById("speed"),g=document.getElementById("start-animation");let h,d=!1,p=0;function F(e){const t=Number(f.value),o=e.frameState.time;p=(p+t*(o-h)/1e6)%2,h=o;const n=r.getCoordinateAt(p>1?2-p:p);s.setCoordinates(n);const a=(0,S.u3)(e);a.setStyle(l.geoMarker),a.drawGeometry(s),P.render()}g.addEventListener("click",(function(){d?(d=!1,g.textContent="Start Animation",u.setGeometry(s),m.un("postrender",F)):(d=!0,h=Date.now(),g.textContent="Stop Animation",m.on("postrender",F),u.setGeometry(null))}))}))}))}},function(e){var t;t=98631,e(e.s=t)}]);
//# sourceMappingURL=feature-move-animation.js.map