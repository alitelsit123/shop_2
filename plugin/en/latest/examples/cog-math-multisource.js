"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[8915],{77604:function(n,a,e){var t=e(22635),s=e(1055),o=e(50463);const d=new t.Z({sources:[{url:"https://s2downloads.eox.at/demo/Sentinel-2/3857/R10m.tif",bands:[3,4],min:0,nodata:0,max:65535},{url:"https://s2downloads.eox.at/demo/Sentinel-2/3857/R60m.tif",bands:[9],min:0,nodata:0,max:65535}]});d.setAttributions("<a href='https://s2maps.eu'>Sentinel-2 cloudless</a> by <a href='https://eox.at/'>EOX IT Services GmbH</a> (Contains modified Copernicus Sentinel data 2019)");const i=["/",["-",["band",2],["band",1]],["+",["band",2],["band",1]]],b=["/",["-",["band",3],["band",1]],["+",["band",3],["band",1]]];new s.Z({target:"map",layers:[new o.Z({style:{color:["color",["*",255,["abs",["-",i,b]]],["*",255,i],["*",255,b],["band",4]]},source:d})],view:d.getView()})}},function(n){var a;a=77604,n(n.s=a)}]);
//# sourceMappingURL=cog-math-multisource.js.map