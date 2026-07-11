import{o as e,u as t}from"./pii-meta-D_9Jsj4F.js";import{o as n,p as r}from"./validation-ui-DZX4dsJT.js";function i(e){let t=e.trim();return t.startsWith(`[`)&&t.endsWith(`]`)?t.slice(1,-1).split(`,`).map(e=>e.trim()).filter(Boolean):t.split(`,`).map(e=>e.trim()).filter(Boolean)}function a(e,t){let n=t.trim();n&&(n.startsWith(`This model contains data from the '`)&&n.endsWith(`' table.`)?e.sourceTable=n.slice(37,-9):n.startsWith(`PII details version:`)?e.piiVersion=n.slice(20).trim():n.startsWith(`Access mode: role-based`)?e.useAccessRoles=!0:n.startsWith(`Access mode: rule-based`)&&(e.useAccessRoles=!1))}function o(t){if(!t.trim())return{ok:!1,error:{code:`empty`}};if(/^\s*sources\s*:/m.test(t)&&!/^\s*models\s*:/m.test(t))return{ok:!1,error:{code:`unsupported`}};try{let n=e();n.modelDescription=``,n.descriptionExtra=``,n.columns=[];let r=t.split(`
`),o=!1,s=[],c=null,l=!1,u=!1,d=!1;for(let e=0;e<r.length;e+=1){let t=r[e],f=t.trim();if(o){if(/^\s{4}\S/.test(t)&&!t.startsWith(`      `))o=!1;else if(t.startsWith(`      `)){let e=t.slice(6);a(n,e),s.push(e.trimEnd());continue}else if(!f){s.push(``);continue}}if(!(!f||f.startsWith(`#`))){if(f.startsWith(`version:`)){n.version=Number(f.slice(8).trim())||2;continue}if(!f.startsWith(`pii-reviewed:`)){if(f.startsWith(`access_groups:`)){n.defaultModelAccessGroups=i(f.slice(14));continue}if(f.startsWith(`- name:`)){let e=f.slice(7).trim();if((t.match(/^\s*/)?.[0].length??0)<=4&&!l){n.modelName=e,l=!0,c=null,u=!1,d=!1;continue}c&&n.columns.push(c),c={name:e,category:`none`,description:``},u=!1,d=!1;continue}if(f===`description: |`){o=!0;continue}if(f.startsWith(`description:`)&&f!==`description: |`&&!c&&l){n.modelDescription=f.slice(12).trim();continue}if(f.startsWith(`description:`)&&c){c.description=f.slice(12).trim();continue}if(f===`pii_details:`||f===`pii_recommend:`){d=!0;continue}if(f.startsWith(`category:`)&&c&&d){c.category=f.slice(9).trim();continue}if(f.startsWith(`access_roles:`)){n.useAccessRoles=!0,c&&d&&(c.accessRoles=i(f.slice(13)));continue}if(f===`access_rules:`){c&&d&&(n.useAccessRoles=!1,u=!0);continue}u&&c&&(f.startsWith(`masked:`)&&(n.accessRules.masked=i(f.slice(7))),f.startsWith(`unmasked:`)&&(n.accessRules.unmasked=i(f.slice(9))))}}}for(c&&n.columns.push(c);s.length>0&&!s[s.length-1].trim();)s.pop();return n.modelDescription=s.join(`
`),l?n.columns.length===0?{ok:!1,error:{code:`missing_columns`}}:(n.sourceTable||=`raw.example_table`,n.piiVersion||=`cf38c9353be46d305f35c22a8d926c62`,{ok:!0,state:n}):{ok:!1,error:{code:`missing_model`}}}catch(e){return{ok:!1,error:{code:`invalid_structure`,detail:e instanceof Error?e.message:String(e)}}}}function s(e,t){let i=n(e,r(t.code));return t.line&&(i+=n(e,`validation.yaml.lineSuffix`,{line:t.line})),i}function c(e){let t=e.name?.trim()||`(unnamed)`,n=e.category&&e.category!==`none`?e.category:`none`,r=e.description?.trim();return r?`${t} · ${n} — ${r.length>48?`${r.slice(0,48)}…`:r}`:`${t} · ${n}`}function l(e){let{columns:n,state:r,showAccessRoles:i,t:a,labelKeys:o,inputClass:s,categoryClass:l,renderTypeOptions:u,renderScopeOptions:d,escapeAttr:f}=e;return n.map((e,n)=>{let{piiType:p,scope:m}=t(e.category),h=(e.accessRoles??r.defaultAccessRoles).join(`, `),g=i?`<label class="tools-column-accordion__field">
                    <span class="tools-column-accordion__label">${a(o.accessRoles)}</span>
                    <input class="${s}" type="text" data-field="accessRoles" value="${f(h)}" ${p===`none`?`disabled`:``} />
                   </label>`:``;return`<details class="tools-column-accordion__item" data-column-index="${n}" ${n===0?`open`:``}>
                <summary class="tools-column-accordion__summary">
                    <span class="tools-column-accordion__summary-label">${f(c(e))}</span>
                </summary>
                <div class="tools-column-accordion__body">
                    <div class="tools-column-accordion__grid">
                        <label class="tools-column-accordion__field">
                            <span class="tools-column-accordion__label">${a(o.name)}</span>
                            <input class="${s}" type="text" data-field="name" value="${f(e.name)}" />
                        </label>
                        <label class="tools-column-accordion__field tools-column-accordion__field--full">
                            <span class="tools-column-accordion__label">${a(o.description)}</span>
                            <input class="${s}" type="text" data-field="description" value="${f(e.description??``)}" placeholder="${f(a(o.descriptionHint))}" />
                        </label>
                        <label class="tools-column-accordion__field">
                            <span class="tools-column-accordion__label">${a(o.piiType)}</span>
                            <select class="${s}" data-field="piiType">${u(p)}</select>
                        </label>
                        <label class="tools-column-accordion__field">
                            <span class="tools-column-accordion__label">${a(o.scope)}</span>
                            <select class="${s}" data-field="scope">${d(m)}</select>
                        </label>
                        <label class="tools-column-accordion__field">
                            <span class="tools-column-accordion__label">${a(o.category)}</span>
                            <code class="${l}" data-field="categoryPreview">${f(e.category)}</code>
                        </label>
                        ${g}
                    </div>
                    <div class="tools-column-accordion__actions">
                        <button type="button" class="tools-btn" data-action="remove-row">${a(o.remove)}</button>
                    </div>
                </div>
            </details>`}).join(``)}function u(e,t,n,r,i,a){let o=n[t];if(!o)return;let s=e.querySelector(`[data-field="name"]`).value.trim(),l=e.querySelector(`[data-field="description"]`).value.trim(),u=e.querySelector(`[data-field="piiType"]`).value,d=e.querySelector(`[data-field="scope"]`).value,f=e.querySelector(`[data-field="accessRoles"]`);if(o.name=s,o.description=l,o.category=a(u,d),f&&u!==`none`){let e=i(f.value);o.accessRoles=e.length?e:[...r.defaultAccessRoles]}else delete o.accessRoles;let p=e.querySelector(`[data-field="categoryPreview"]`);p&&(p.textContent=o.category),f&&(f.disabled=u===`none`);let m=e.querySelector(`.tools-column-accordion__summary-label`);m&&(m.textContent=c(o))}export{o as i,u as n,s as r,l as t};