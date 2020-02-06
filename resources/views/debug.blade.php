<script> Sfdump = window.Sfdump || (function (doc) { var refStyle = doc.createElement('style'), rxEsc = /([.*+?^${}()|\[\]\/\\])/g, idRx = /\bsf-dump-\d+-ref[012]\w+\b/, keyHint = 0 <= navigator.platform.toUpperCase().indexOf('MAC') ? 'Cmd' : 'Ctrl', addEventListener = function (e, n, cb) { e.addEventListener(n, cb, false); }; (doc.documentElement.firstElementChild || doc.documentElement.children[0]).appendChild(refStyle); if (!doc.addEventListener) { addEventListener = function (element, eventName, callback) { element.attachEvent('on' + eventName, function (e) { e.preventDefault = function () {e.returnValue = false;}; e.target = e.srcElement; callback(e); }); }; } function toggle(a, recursive) { var s = a.nextSibling || {}, oldClass = s.className, arrow, newClass; if (/\bsf-dump-compact\b/.test(oldClass)) { arrow = '&#9660;'; newClass = 'sf-dump-expanded'; } else if (/\bsf-dump-expanded\b/.test(oldClass)) { arrow = '&#9654;'; newClass = 'sf-dump-compact'; } else { return false; } if (doc.createEvent && s.dispatchEvent) { var event = doc.createEvent('Event'); event.initEvent('sf-dump-expanded' === newClass ? 'sfbeforedumpexpand' : 'sfbeforedumpcollapse', true, false); s.dispatchEvent(event); } a.lastChild.innerHTML = arrow; s.className = s.className.replace(/\bsf-dump-(compact|expanded)\b/, newClass); if (recursive) { try { a = s.querySelectorAll('.'+oldClass); for (s = 0; s < a.length; ++s) { if (-1 == a[s].className.indexOf(newClass)) { a[s].className = newClass; a[s].previousSibling.lastChild.innerHTML = arrow; } } } catch (e) { } } return true; }; function collapse(a, recursive) { var s = a.nextSibling || {}, oldClass = s.className; if (/\bsf-dump-expanded\b/.test(oldClass)) { toggle(a, recursive); return true; } return false; }; function expand(a, recursive) { var s = a.nextSibling || {}, oldClass = s.className; if (/\bsf-dump-compact\b/.test(oldClass)) { toggle(a, recursive); return true; } return false; }; function collapseAll(root) { var a = root.querySelector('a.sf-dump-toggle'); if (a) { collapse(a, true); expand(a); return true; } return false; } function reveal(node) { var previous, parents = []; while ((node = node.parentNode || {}) && (previous = node.previousSibling) && 'A' === previous.tagName) { parents.push(previous); } if (0 !== parents.length) { parents.forEach(function (parent) { expand(parent); }); return true; } return false; } function highlight(root, activeNode, nodes) { resetHighlightedNodes(root); Array.from(nodes||[]).forEach(function (node) { if (!/\bsf-dump-highlight\b/.test(node.className)) { node.className = node.className + ' sf-dump-highlight'; } }); if (!/\bsf-dump-highlight-active\b/.test(activeNode.className)) { activeNode.className = activeNode.className + ' sf-dump-highlight-active'; } } function resetHighlightedNodes(root) { Array.from(root.querySelectorAll('.sf-dump-str, .sf-dump-key, .sf-dump-public, .sf-dump-protected, .sf-dump-private')).forEach(function (strNode) { strNode.className = strNode.className.replace(/\bsf-dump-highlight\b/, ''); strNode.className = strNode.className.replace(/\bsf-dump-highlight-active\b/, ''); }); } return function (root, x) { root = doc.getElementById(root); var indentRx = new RegExp('^('+(root.getAttribute('data-indent-pad') || ' ').replace(rxEsc, '\\$1')+')+', 'm'), options = {"maxDepth":1,"maxStringLength":160,"fileLinkFormat":false}, elt = root.getElementsByTagName('A'), len = elt.length, i = 0, s, h, t = []; while (i < len) t.push(elt[i++]); for (i in x) { options[i] = x[i]; } function a(e, f) { addEventListener(root, e, function (e, n) { if ('A' == e.target.tagName) { f(e.target, e); } else if ('A' == e.target.parentNode.tagName) { f(e.target.parentNode, e); } else { n = /\bsf-dump-ellipsis\b/.test(e.target.className) ? e.target.parentNode : e.target; if ((n = n.nextElementSibling) && 'A' == n.tagName) { if (!/\bsf-dump-toggle\b/.test(n.className)) { n = n.nextElementSibling || n; } f(n, e, true); } } }); }; function isCtrlKey(e) { return e.ctrlKey || e.metaKey; } function xpathString(str) { var parts = str.match(/[^'"]+|['"]/g).map(function (part) { if ("'" == part) { return '"\'"'; } if ('"' == part) { return "'\"'"; } return "'" + part + "'"; }); return "concat(" + parts.join(",") + ", '')"; } function xpathHasClass(className) { return "contains(concat(' ', normalize-space(@class), ' '), ' " + className +" ')"; } addEventListener(root, 'mouseover', function (e) { if ('' != refStyle.innerHTML) { refStyle.innerHTML = ''; } }); a('mouseover', function (a, e, c) { if (c) { e.target.style.cursor = "pointer"; } else if (a = idRx.exec(a.className)) { try { refStyle.innerHTML = 'pre.sf-dump .'+a[0]+'{background-color: #B729D9; color: #FFF !important; border-radius: 2px}'; } catch (e) { } } }); a('click', function (a, e, c) { if (/\bsf-dump-toggle\b/.test(a.className)) { e.preventDefault(); if (!toggle(a, isCtrlKey(e))) { var r = doc.getElementById(a.getAttribute('href').substr(1)), s = r.previousSibling, f = r.parentNode, t = a.parentNode; t.replaceChild(r, a); f.replaceChild(a, s); t.insertBefore(s, r); f = f.firstChild.nodeValue.match(indentRx); t = t.firstChild.nodeValue.match(indentRx); if (f && t && f[0] !== t[0]) { r.innerHTML = r.innerHTML.replace(new RegExp('^'+f[0].replace(rxEsc, '\\$1'), 'mg'), t[0]); } if (/\bsf-dump-compact\b/.test(r.className)) { toggle(s, isCtrlKey(e)); } } if (c) { } else if (doc.getSelection) { try { doc.getSelection().removeAllRanges(); } catch (e) { doc.getSelection().empty(); } } else { doc.selection.empty(); } } else if (/\bsf-dump-str-toggle\b/.test(a.className)) { e.preventDefault(); e = a.parentNode.parentNode; e.className = e.className.replace(/\bsf-dump-str-(expand|collapse)\b/, a.parentNode.className); } }); elt = root.getElementsByTagName('SAMP'); len = elt.length; i = 0; while (i < len) t.push(elt[i++]); len = t.length; for (i = 0; i < len; ++i) { elt = t[i]; if ('SAMP' == elt.tagName) { a = elt.previousSibling || {}; if ('A' != a.tagName) { a = doc.createElement('A'); a.className = 'sf-dump-ref'; elt.parentNode.insertBefore(a, elt); } else { a.innerHTML += ' '; } a.title = (a.title ? a.title+'\n[' : '[')+keyHint+'+click] Expand all children'; a.innerHTML += '<span>&#9660;</span>'; a.className += ' sf-dump-toggle'; x = 1; if ('sf-dump' != elt.parentNode.className) { x += elt.parentNode.getAttribute('data-depth')/1; } elt.setAttribute('data-depth', x); var className = elt.className; elt.className = 'sf-dump-expanded'; if (className ? 'sf-dump-expanded' !== className : (x > options.maxDepth)) { toggle(a); } } else if (/\bsf-dump-ref\b/.test(elt.className) && (a = elt.getAttribute('href'))) { a = a.substr(1); elt.className += ' '+a; if (/[\[{]$/.test(elt.previousSibling.nodeValue)) { a = a != elt.nextSibling.id && doc.getElementById(a); try { s = a.nextSibling; elt.appendChild(a); s.parentNode.insertBefore(a, s); if (/^[@#]/.test(elt.innerHTML)) { elt.innerHTML += ' <span>&#9654;</span>'; } else { elt.innerHTML = '<span>&#9654;</span>'; elt.className = 'sf-dump-ref'; } elt.className += ' sf-dump-toggle'; } catch (e) { if ('&' == elt.innerHTML.charAt(0)) { elt.innerHTML = '&hellip;'; elt.className = 'sf-dump-ref'; } } } } } if (doc.evaluate && Array.from && root.children.length > 1) { root.setAttribute('tabindex', 0); SearchState = function () { this.nodes = []; this.idx = 0; }; SearchState.prototype = { next: function () { if (this.isEmpty()) { return this.current(); } this.idx = this.idx < (this.nodes.length - 1) ? this.idx + 1 : 0; return this.current(); }, previous: function () { if (this.isEmpty()) { return this.current(); } this.idx = this.idx > 0 ? this.idx - 1 : (this.nodes.length - 1); return this.current(); }, isEmpty: function () { return 0 === this.count(); }, current: function () { if (this.isEmpty()) { return null; } return this.nodes[this.idx]; }, reset: function () { this.nodes = []; this.idx = 0; }, count: function () { return this.nodes.length; }, }; function showCurrent(state) { var currentNode = state.current(), currentRect, searchRect; if (currentNode) { reveal(currentNode); highlight(root, currentNode, state.nodes); if ('scrollIntoView' in currentNode) { currentNode.scrollIntoView(true); currentRect = currentNode.getBoundingClientRect(); searchRect = search.getBoundingClientRect(); if (currentRect.top < (searchRect.top + searchRect.height)) { window.scrollBy(0, -(searchRect.top + searchRect.height + 5)); } } } counter.textContent = (state.isEmpty() ? 0 : state.idx + 1) + ' of ' + state.count(); } var search = doc.createElement('div'); search.className = 'sf-dump-search-wrapper sf-dump-search-hidden'; search.innerHTML = ' <input type="text" class="sf-dump-search-input"> <span class="sf-dump-search-count">0 of 0<\/span> <button type="button" class="sf-dump-search-input-previous" tabindex="-1"> <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1683 1331l-166 165q-19 19-45 19t-45-19L896 965l-531 531q-19 19-45 19t-45-19l-166-165q-19-19-19-45.5t19-45.5l742-741q19-19 45-19t45 19l742 741q19 19 19 45.5t-19 45.5z"\/><\/svg> <\/button> <button type="button" class="sf-dump-search-input-next" tabindex="-1"> <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1683 808l-742 741q-19 19-45 19t-45-19L109 808q-19-19-19-45.5t19-45.5l166-165q19-19 45-19t45 19l531 531 531-531q19-19 45-19t45 19l166 165q19 19 19 45.5t-19 45.5z"\/><\/svg> <\/button> '; root.insertBefore(search, root.firstChild); var state = new SearchState(); var searchInput = search.querySelector('.sf-dump-search-input'); var counter = search.querySelector('.sf-dump-search-count'); var searchInputTimer = 0; var previousSearchQuery = ''; addEventListener(searchInput, 'keyup', function (e) { var searchQuery = e.target.value; /* Don't perform anything if the pressed key didn't change the query */ if (searchQuery === previousSearchQuery) { return; } previousSearchQuery = searchQuery; clearTimeout(searchInputTimer); searchInputTimer = setTimeout(function () { state.reset(); collapseAll(root); resetHighlightedNodes(root); if ('' === searchQuery) { counter.textContent = '0 of 0'; return; } var classMatches = [ "sf-dump-str", "sf-dump-key", "sf-dump-public", "sf-dump-protected", "sf-dump-private", ].map(xpathHasClass).join(' or '); var xpathResult = doc.evaluate('.//span[' + classMatches + '][contains(translate(child::text(), ' + xpathString(searchQuery.toUpperCase()) + ', ' + xpathString(searchQuery.toLowerCase()) + '), ' + xpathString(searchQuery.toLowerCase()) + ')]', root, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null); while (node = xpathResult.iterateNext()) state.nodes.push(node); showCurrent(state); }, 400); }); Array.from(search.querySelectorAll('.sf-dump-search-input-next, .sf-dump-search-input-previous')).forEach(function (btn) { addEventListener(btn, 'click', function (e) { e.preventDefault(); -1 !== e.target.className.indexOf('next') ? state.next() : state.previous(); searchInput.focus(); collapseAll(root); showCurrent(state); }) }); addEventListener(root, 'keydown', function (e) { var isSearchActive = !/\bsf-dump-search-hidden\b/.test(search.className); if ((114 === e.keyCode && !isSearchActive) || (isCtrlKey(e) && 70 === e.keyCode)) { /* F3 or CMD/CTRL + F */ if (70 === e.keyCode && document.activeElement === searchInput) { /* * If CMD/CTRL + F is hit while having focus on search input, * the user probably meant to trigger browser search instead. * Let the browser execute its behavior: */ return; } e.preventDefault(); search.className = search.className.replace(/\bsf-dump-search-hidden\b/, ''); searchInput.focus(); } else if (isSearchActive) { if (27 === e.keyCode) { /* ESC key */ search.className += ' sf-dump-search-hidden'; e.preventDefault(); resetHighlightedNodes(root); searchInput.value = ''; } else if ( (isCtrlKey(e) && 71 === e.keyCode) /* CMD/CTRL + G */ || 13 === e.keyCode /* Enter */ || 114 === e.keyCode /* F3 */ ) { e.preventDefault(); e.shiftKey ? state.previous() : state.next(); collapseAll(root); showCurrent(state); } } }); } if (0 >= options.maxStringLength) { return; } try { elt = root.querySelectorAll('.sf-dump-str'); len = elt.length; i = 0; t = []; while (i < len) t.push(elt[i++]); len = t.length; for (i = 0; i < len; ++i) { elt = t[i]; s = elt.innerText || elt.textContent; x = s.length - options.maxStringLength; if (0 < x) { h = elt.innerHTML; elt[elt.innerText ? 'innerText' : 'textContent'] = s.substring(0, options.maxStringLength); elt.className += ' sf-dump-str-collapse'; elt.innerHTML = '<span class=sf-dump-str-collapse>'+h+'<a class="sf-dump-ref sf-dump-str-toggle" title="Collapse"> &#9664;</a></span>'+ '<span class=sf-dump-str-expand>'+elt.innerHTML+'<a class="sf-dump-ref sf-dump-str-toggle" title="'+x+' remaining characters"> &#9654;</a></span>'; } } } catch (e) { } }; })(document); </script><style> pre.sf-dump { display: block; white-space: pre; padding: 5px; overflow: initial !important; } pre.sf-dump:after { content: ""; visibility: hidden; display: block; height: 0; clear: both; } pre.sf-dump span { display: inline; } pre.sf-dump .sf-dump-compact { display: none; } pre.sf-dump a { text-decoration: none; cursor: pointer; border: 0; outline: none; color: inherit; } pre.sf-dump img { max-width: 50em; max-height: 50em; margin: .5em 0 0 0; padding: 0; background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAAAAAA6mKC9AAAAHUlEQVQY02O8zAABilCaiQEN0EeA8QuUcX9g3QEAAjcC5piyhyEAAAAASUVORK5CYII=) #D3D3D3; } pre.sf-dump .sf-dump-ellipsis { display: inline-block; overflow: visible; text-overflow: ellipsis; max-width: 5em; white-space: nowrap; overflow: hidden; vertical-align: top; } pre.sf-dump .sf-dump-ellipsis+.sf-dump-ellipsis { max-width: none; } pre.sf-dump code { display:inline; padding:0; background:none; } .sf-dump-str-collapse .sf-dump-str-collapse { display: none; } .sf-dump-str-expand .sf-dump-str-expand { display: none; } .sf-dump-public.sf-dump-highlight, .sf-dump-protected.sf-dump-highlight, .sf-dump-private.sf-dump-highlight, .sf-dump-str.sf-dump-highlight, .sf-dump-key.sf-dump-highlight { background: rgba(111, 172, 204, 0.3); border: 1px solid #7DA0B1; border-radius: 3px; } .sf-dump-public.sf-dump-highlight-active, .sf-dump-protected.sf-dump-highlight-active, .sf-dump-private.sf-dump-highlight-active, .sf-dump-str.sf-dump-highlight-active, .sf-dump-key.sf-dump-highlight-active { background: rgba(253, 175, 0, 0.4); border: 1px solid #ffa500; border-radius: 3px; } pre.sf-dump .sf-dump-search-hidden { display: none !important; } pre.sf-dump .sf-dump-search-wrapper { font-size: 0; white-space: nowrap; margin-bottom: 5px; display: flex; position: -webkit-sticky; position: sticky; top: 5px; } pre.sf-dump .sf-dump-search-wrapper > * { vertical-align: top; box-sizing: border-box; height: 21px; font-weight: normal; border-radius: 0; background: #FFF; color: #757575; border: 1px solid #BBB; } pre.sf-dump .sf-dump-search-wrapper > input.sf-dump-search-input { padding: 3px; height: 21px; font-size: 12px; border-right: none; border-top-left-radius: 3px; border-bottom-left-radius: 3px; color: #000; min-width: 15px; width: 100%; } pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next, pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-previous { background: #F2F2F2; outline: none; border-left: none; font-size: 0; line-height: 0; } pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next { border-top-right-radius: 3px; border-bottom-right-radius: 3px; } pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next > svg, pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-previous > svg { pointer-events: none; width: 12px; height: 12px; } pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-count { display: inline-block; padding: 0 5px; margin: 0; border-left: none; line-height: 21px; font-size: 12px; }pre.sf-dump, pre.sf-dump .sf-dump-default{background-color:#18171B; color:#FF8400; line-height:1.2em; font:12px Menlo, Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:99999; word-break: break-all}pre.sf-dump .sf-dump-num{font-weight:bold; color:#1299DA}pre.sf-dump .sf-dump-const{font-weight:bold}pre.sf-dump .sf-dump-str{font-weight:bold; color:#56DB3A}pre.sf-dump .sf-dump-note{color:#1299DA}pre.sf-dump .sf-dump-ref{color:#A0A0A0}pre.sf-dump .sf-dump-public{color:#FFFFFF}pre.sf-dump .sf-dump-protected{color:#FFFFFF}pre.sf-dump .sf-dump-private{color:#FFFFFF}pre.sf-dump .sf-dump-meta{color:#B729D9}pre.sf-dump .sf-dump-key{color:#56DB3A}pre.sf-dump .sf-dump-index{color:#1299DA}pre.sf-dump .sf-dump-ellipsis{color:#FF8400}pre.sf-dump .sf-dump-ns{user-select:none;}pre.sf-dump .sf-dump-ellipsis-note{color:#1299DA}</style><pre class=sf-dump id=sf-dump-1538836868 data-indent-pad="  "><span class=sf-dump-note>Illuminate\Database\Eloquent\Builder</span> {<a class=sf-dump-ref>#264</a><samp>
  #<span class=sf-dump-protected title="Protected property">query</span>: <span class=sf-dump-note title="Illuminate\Database\Query\Builder
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database\Query</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Builder</span> {<a class=sf-dump-ref>#263</a><samp>
    +<span class=sf-dump-public title="Public property">connection</span>: <span class=sf-dump-note title="Illuminate\Database\MySqlConnection
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>MySqlConnection</span> {<a class=sf-dump-ref>#259</a><samp>
      #<span class=sf-dump-protected title="Protected property">pdo</span>: <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#258</a><samp>
        <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Database\Connectors\ConnectionFactory
48 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Database\Connectors</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>ConnectionFactory</span>"
        <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Database\Connectors\ConnectionFactory
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database\Connectors</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ConnectionFactory</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref244 title="2 occurrences">#44</a> &hellip;}
        <span class=sf-dump-meta>use</span>: {<samp>
          <span class=sf-dump-meta>$config</span>: <span class=sf-dump-note>array:15</span> [<samp>
            "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="5 characters">mysql</span>"
            "<span class=sf-dump-key>host</span>" => "<span class=sf-dump-str title="9 characters">127.0.0.1</span>"
            "<span class=sf-dump-key>port</span>" => "<span class=sf-dump-str title="4 characters">3306</span>"
            "<span class=sf-dump-key>database</span>" => "<span class=sf-dump-str title="10 characters">grower-lab</span>"
            "<span class=sf-dump-key>username</span>" => "<span class=sf-dump-str title="4 characters">root</span>"
            "<span class=sf-dump-key>password</span>" => ""
            "<span class=sf-dump-key>unix_socket</span>" => ""
            "<span class=sf-dump-key>charset</span>" => "<span class=sf-dump-str title="7 characters">utf8mb4</span>"
            "<span class=sf-dump-key>collation</span>" => "<span class=sf-dump-str title="18 characters">utf8mb4_unicode_ci</span>"
            "<span class=sf-dump-key>prefix</span>" => ""
            "<span class=sf-dump-key>prefix_indexes</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>strict</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>engine</span>" => <span class=sf-dump-const>null</span>
            "<span class=sf-dump-key>options</span>" => []
            "<span class=sf-dump-key>name</span>" => "<span class=sf-dump-str title="5 characters">mysql</span>"
          </samp>]
        </samp>}
        <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Database\Connectors\ConnectionFactory.php
144 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Database\Connectors\ConnectionFactory.php</span>"
        <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">177 to 189</span>"
      </samp>}
      #<span class=sf-dump-protected title="Protected property">readPdo</span>: <span class=sf-dump-const>null</span>
      #<span class=sf-dump-protected title="Protected property">database</span>: "<span class=sf-dump-str title="10 characters">grower-lab</span>"
      #<span class=sf-dump-protected title="Protected property">tablePrefix</span>: ""
      #<span class=sf-dump-protected title="Protected property">config</span>: <span class=sf-dump-note>array:15</span> [<samp>
        "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="5 characters">mysql</span>"
        "<span class=sf-dump-key>host</span>" => "<span class=sf-dump-str title="9 characters">127.0.0.1</span>"
        "<span class=sf-dump-key>port</span>" => "<span class=sf-dump-str title="4 characters">3306</span>"
        "<span class=sf-dump-key>database</span>" => "<span class=sf-dump-str title="10 characters">grower-lab</span>"
        "<span class=sf-dump-key>username</span>" => "<span class=sf-dump-str title="4 characters">root</span>"
        "<span class=sf-dump-key>password</span>" => ""
        "<span class=sf-dump-key>unix_socket</span>" => ""
        "<span class=sf-dump-key>charset</span>" => "<span class=sf-dump-str title="7 characters">utf8mb4</span>"
        "<span class=sf-dump-key>collation</span>" => "<span class=sf-dump-str title="18 characters">utf8mb4_unicode_ci</span>"
        "<span class=sf-dump-key>prefix</span>" => ""
        "<span class=sf-dump-key>prefix_indexes</span>" => <span class=sf-dump-const>true</span>
        "<span class=sf-dump-key>strict</span>" => <span class=sf-dump-const>true</span>
        "<span class=sf-dump-key>engine</span>" => <span class=sf-dump-const>null</span>
        "<span class=sf-dump-key>options</span>" => []
        "<span class=sf-dump-key>name</span>" => "<span class=sf-dump-str title="5 characters">mysql</span>"
      </samp>]
      #<span class=sf-dump-protected title="Protected property">reconnector</span>: <span class=sf-dump-note>Closure($connection)</span> {<a class=sf-dump-ref>#30</a><samp>
        <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Database\DatabaseManager
35 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>DatabaseManager</span>"
        <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Database\DatabaseManager
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>DatabaseManager</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref250 title="2 occurrences">#50</a> &hellip;}
        <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Database\DatabaseManager.php
131 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Database\DatabaseManager.php</span>"
        <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">64 to 66</span>"
      </samp>}
      #<span class=sf-dump-protected title="Protected property">queryGrammar</span>: <span class=sf-dump-note title="Illuminate\Database\Query\Grammars\MySqlGrammar
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database\Query\Grammars</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>MySqlGrammar</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2261 title="2 occurrences">#261</a><samp id=sf-dump-1538836868-ref2261>
        #<span class=sf-dump-protected title="Protected property">operators</span>: <span class=sf-dump-note>array:1</span> [<samp>
          <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="11 characters">sounds like</span>"
        </samp>]
        #<span class=sf-dump-protected title="Protected property">selectComponents</span>: <span class=sf-dump-note>array:11</span> [<samp>
          <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="9 characters">aggregate</span>"
          <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="7 characters">columns</span>"
          <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="4 characters">from</span>"
          <span class=sf-dump-index>3</span> => "<span class=sf-dump-str title="5 characters">joins</span>"
          <span class=sf-dump-index>4</span> => "<span class=sf-dump-str title="6 characters">wheres</span>"
          <span class=sf-dump-index>5</span> => "<span class=sf-dump-str title="6 characters">groups</span>"
          <span class=sf-dump-index>6</span> => "<span class=sf-dump-str title="7 characters">havings</span>"
          <span class=sf-dump-index>7</span> => "<span class=sf-dump-str title="6 characters">orders</span>"
          <span class=sf-dump-index>8</span> => "<span class=sf-dump-str title="5 characters">limit</span>"
          <span class=sf-dump-index>9</span> => "<span class=sf-dump-str title="6 characters">offset</span>"
          <span class=sf-dump-index>10</span> => "<span class=sf-dump-str title="4 characters">lock</span>"
        </samp>]
        #<span class=sf-dump-protected title="Protected property">tablePrefix</span>: ""
      </samp>}
      #<span class=sf-dump-protected title="Protected property">schemaGrammar</span>: <span class=sf-dump-const>null</span>
      #<span class=sf-dump-protected title="Protected property">postProcessor</span>: <span class=sf-dump-note title="Illuminate\Database\Query\Processors\MySqlProcessor
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database\Query\Processors</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>MySqlProcessor</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2262 title="2 occurrences">#262</a>}
      #<span class=sf-dump-protected title="Protected property">events</span>: <span class=sf-dump-note title="Illuminate\Events\Dispatcher
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Dispatcher</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref235 title="9 occurrences">#35</a><samp id=sf-dump-1538836868-ref235>
        #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a><samp id=sf-dump-1538836868-ref22>
          #<span class=sf-dump-protected title="Protected property">basePath</span>: "<span class=sf-dump-str title="62 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app</span>"
          #<span class=sf-dump-protected title="Protected property">hasBeenBootstrapped</span>: <span class=sf-dump-const>true</span>
          #<span class=sf-dump-protected title="Protected property">booted</span>: <span class=sf-dump-const>true</span>
          #<span class=sf-dump-protected title="Protected property">bootingCallbacks</span>: <span class=sf-dump-note>array:1</span> [<samp>
            <span class=sf-dump-index>0</span> => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#159</a><samp>
              <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Foundation\Application
33 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Application</span>"
              <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
              <span class=sf-dump-meta>use</span>: {<samp>
                <span class=sf-dump-meta>$instance</span>: <span class=sf-dump-note title="Illuminate\Queue\QueueServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueueServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2152 title="13 occurrences">#152</a><samp id=sf-dump-1538836868-ref2152>
                  #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                </samp>}
              </samp>}
              <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Foundation\Application.php
129 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Foundation\Application.php</span>"
              <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">753 to 755</span>"
            </samp>}
          </samp>]
          #<span class=sf-dump-protected title="Protected property">bootedCallbacks</span>: <span class=sf-dump-note>array:1</span> [<samp>
            <span class=sf-dump-index>0</span> => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#191</a><samp>
              <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Foundation\Support\Providers\RouteServiceProvider
60 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Foundation\Support\Providers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>RouteServiceProvider</span>"
              <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="App\Providers\RouteServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">App\Providers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RouteServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2131 title="2 occurrences">#131</a><samp id=sf-dump-1538836868-ref2131>
                #<span class=sf-dump-protected title="Protected property">namespace</span>: "<span class=sf-dump-str title="20 characters">App\Http\Controllers</span>"
                #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
              </samp>}
              <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Foundation\Support\Providers\RouteServiceProvider.php
156 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Foundation\Support\Providers\RouteServiceProvider.php</span>"
              <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">38 to 41</span>"
            </samp>}
          </samp>]
          #<span class=sf-dump-protected title="Protected property">terminatingCallbacks</span>: []
          #<span class=sf-dump-protected title="Protected property">serviceProviders</span>: <span class=sf-dump-note>array:23</span> [<samp>
            <span class=sf-dump-index>0</span> => <span class=sf-dump-note title="Illuminate\Events\EventServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>EventServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref214 title="3 occurrences">#14</a> &hellip;}
            <span class=sf-dump-index>1</span> => <span class=sf-dump-note title="Illuminate\Log\LogServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Log</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>LogServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref216 title="2 occurrences">#16</a><samp id=sf-dump-1538836868-ref216>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>2</span> => <span class=sf-dump-note title="Illuminate\Routing\RoutingServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RoutingServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref218 title="13 occurrences">#18</a><samp id=sf-dump-1538836868-ref218>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>3</span> => <span class=sf-dump-note title="Illuminate\Auth\AuthServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AuthServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref247 title="8 occurrences">#47</a><samp id=sf-dump-1538836868-ref247>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>4</span> => <span class=sf-dump-note title="Illuminate\Cookie\CookieServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Cookie</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>CookieServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref260 title="2 occurrences">#60</a><samp id=sf-dump-1538836868-ref260>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>5</span> => <span class=sf-dump-note title="Illuminate\Database\DatabaseServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>DatabaseServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref262 title="7 occurrences">#62</a><samp id=sf-dump-1538836868-ref262>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>6</span> => <span class=sf-dump-note title="Illuminate\Encryption\EncryptionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Encryption</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>EncryptionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref269 title="2 occurrences">#69</a><samp id=sf-dump-1538836868-ref269>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>7</span> => <span class=sf-dump-note title="Illuminate\Filesystem\FilesystemServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Filesystem</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>FilesystemServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref271 title="5 occurrences">#71</a><samp id=sf-dump-1538836868-ref271>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>8</span> => <span class=sf-dump-note title="Illuminate\Foundation\Providers\FormRequestServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation\Providers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>FormRequestServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref277 title="4 occurrences">#77</a><samp id=sf-dump-1538836868-ref277>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>9</span> => <span class=sf-dump-note title="Illuminate\Foundation\Providers\FoundationServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation\Providers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>FoundationServiceProvider</span> {<a class=sf-dump-ref>#76</a><samp>
              #<span class=sf-dump-protected title="Protected property">providers</span>: <span class=sf-dump-note>array:1</span> [<samp>
                <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="58 characters">Illuminate\Foundation\Providers\FormRequestServiceProvider</span>"
              </samp>]
              #<span class=sf-dump-protected title="Protected property">instances</span>: <span class=sf-dump-note>array:1</span> [<samp>
                <span class=sf-dump-index>0</span> => <span class=sf-dump-note title="Illuminate\Foundation\Providers\FormRequestServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation\Providers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>FormRequestServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref277 title="4 occurrences">#77</a>}
              </samp>]
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>10</span> => <span class=sf-dump-note title="Illuminate\Notifications\NotificationServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Notifications</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>NotificationServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref281 title="3 occurrences">#81</a><samp id=sf-dump-1538836868-ref281>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>11</span> => <span class=sf-dump-note title="Illuminate\Pagination\PaginationServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Pagination</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>PaginationServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref283 title="2 occurrences">#83</a><samp id=sf-dump-1538836868-ref283>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>12</span> => <span class=sf-dump-note title="Illuminate\Session\SessionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Session</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>SessionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref287 title="3 occurrences">#87</a><samp id=sf-dump-1538836868-ref287>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>13</span> => <span class=sf-dump-note title="Illuminate\View\ViewServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\View</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ViewServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref291 title="6 occurrences">#91</a><samp id=sf-dump-1538836868-ref291>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>14</span> => <span class=sf-dump-note title="Facade\Ignition\IgnitionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>IgnitionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref296 title="10 occurrences">#96</a><samp id=sf-dump-1538836868-ref296>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>15</span> => <span class=sf-dump-note title="Fideloper\Proxy\TrustedProxyServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Fideloper\Proxy</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>TrustedProxyServiceProvider</span> {<a class=sf-dump-ref>#118</a><samp>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>16</span> => <span class=sf-dump-note title="Carbon\Laravel\ServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Carbon\Laravel</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ServiceProvider</span> {<a class=sf-dump-ref>#121</a><samp>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>17</span> => <span class=sf-dump-note title="NunoMaduro\Collision\Adapters\Laravel\CollisionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">NunoMaduro\Collision\Adapters\Laravel</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>CollisionServiceProvider</span> {<a class=sf-dump-ref>#117</a><samp>
              #<span class=sf-dump-protected title="Protected property">defer</span>: <span class=sf-dump-const>true</span>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>18</span> => <span class=sf-dump-note title="App\Providers\AppServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">App\Providers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AppServiceProvider</span> {<a class=sf-dump-ref>#122</a><samp>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>19</span> => <span class=sf-dump-note title="App\Providers\AuthServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">App\Providers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AuthServiceProvider</span> {<a class=sf-dump-ref>#129</a><samp>
              #<span class=sf-dump-protected title="Protected property">policies</span>: []
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>20</span> => <span class=sf-dump-note title="App\Providers\EventServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">App\Providers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>EventServiceProvider</span> {<a class=sf-dump-ref>#130</a><samp>
              #<span class=sf-dump-protected title="Protected property">listen</span>: <span class=sf-dump-note>array:1</span> [<samp>
                "<span class=sf-dump-key>Illuminate\Auth\Events\Registered</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                  <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="59 characters">Illuminate\Auth\Listeners\SendEmailVerificationNotification</span>"
                </samp>]
              </samp>]
              #<span class=sf-dump-protected title="Protected property">subscribe</span>: []
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            <span class=sf-dump-index>21</span> => <span class=sf-dump-note title="App\Providers\RouteServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">App\Providers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RouteServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2131 title="2 occurrences">#131</a>}
            <span class=sf-dump-index>22</span> => <span class=sf-dump-note title="Illuminate\Queue\QueueServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueueServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2152 title="13 occurrences">#152</a>}
          </samp>]
          #<span class=sf-dump-protected title="Protected property">loadedProviders</span>: <span class=sf-dump-note>array:23</span> [<samp>
            "<span class=sf-dump-key>Illuminate\Events\EventServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Log\LogServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Routing\RoutingServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Auth\AuthServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Cookie\CookieServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Database\DatabaseServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Encryption\EncryptionServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Filesystem\FilesystemServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Foundation\Providers\FormRequestServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Foundation\Providers\FoundationServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Notifications\NotificationServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Pagination\PaginationServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Session\SessionServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\View\ViewServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Facade\Ignition\IgnitionServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Fideloper\Proxy\TrustedProxyServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Carbon\Laravel\ServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>NunoMaduro\Collision\Adapters\Laravel\CollisionServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>App\Providers\AppServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>App\Providers\AuthServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>App\Providers\EventServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>App\Providers\RouteServiceProvider</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Queue\QueueServiceProvider</span>" => <span class=sf-dump-const>true</span>
          </samp>]
          #<span class=sf-dump-protected title="Protected property">deferredServices</span>: <span class=sf-dump-note>array:104</span> [<samp>
            "<span class=sf-dump-key>Illuminate\Broadcasting\BroadcastManager</span>" => "<span class=sf-dump-str title="48 characters">Illuminate\Broadcasting\BroadcastServiceProvider</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Broadcasting\Factory</span>" => "<span class=sf-dump-str title="48 characters">Illuminate\Broadcasting\BroadcastServiceProvider</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Broadcasting\Broadcaster</span>" => "<span class=sf-dump-str title="48 characters">Illuminate\Broadcasting\BroadcastServiceProvider</span>"
            "<span class=sf-dump-key>Illuminate\Bus\Dispatcher</span>" => "<span class=sf-dump-str title="33 characters">Illuminate\Bus\BusServiceProvider</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Bus\Dispatcher</span>" => "<span class=sf-dump-str title="33 characters">Illuminate\Bus\BusServiceProvider</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Bus\QueueingDispatcher</span>" => "<span class=sf-dump-str title="33 characters">Illuminate\Bus\BusServiceProvider</span>"
            "<span class=sf-dump-key>cache</span>" => "<span class=sf-dump-str title="37 characters">Illuminate\Cache\CacheServiceProvider</span>"
            "<span class=sf-dump-key>cache.store</span>" => "<span class=sf-dump-str title="37 characters">Illuminate\Cache\CacheServiceProvider</span>"
            "<span class=sf-dump-key>cache.psr6</span>" => "<span class=sf-dump-str title="37 characters">Illuminate\Cache\CacheServiceProvider</span>"
            "<span class=sf-dump-key>memcached.connector</span>" => "<span class=sf-dump-str title="37 characters">Illuminate\Cache\CacheServiceProvider</span>"
            "<span class=sf-dump-key>command.cache.clear</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.cache.forget</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.clear-compiled</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.auth.resets.clear</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.config.cache</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.config.clear</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.db.wipe</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.down</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.environment</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.event.cache</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.event.clear</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.event.list</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.key.generate</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.optimize</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.optimize.clear</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.package.discover</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.preset</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.queue.failed</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.queue.flush</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.queue.forget</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.queue.listen</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.queue.restart</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.queue.retry</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.queue.work</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.route.cache</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.route.clear</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.route.list</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.seed</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>Illuminate\Console\Scheduling\ScheduleFinishCommand</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>Illuminate\Console\Scheduling\ScheduleRunCommand</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.storage.link</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.up</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.view.cache</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.view.clear</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.cache.table</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.channel.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.console.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.controller.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.event.generate</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.event.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.exception.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.factory.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.job.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.listener.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.mail.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.middleware.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.model.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.notification.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.notification.table</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.observer.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.policy.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.provider.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.queue.failed-table</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.queue.table</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.request.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.resource.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.rule.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.seeder.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.session.table</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.serve</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.test.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.vendor.publish</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>migrator</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>migration.repository</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>migration.creator</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.migrate</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.migrate.fresh</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.migrate.install</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.migrate.refresh</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.migrate.reset</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.migrate.rollback</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.migrate.status</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>command.migrate.make</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>composer</span>" => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
            "<span class=sf-dump-key>hash</span>" => "<span class=sf-dump-str title="38 characters">Illuminate\Hashing\HashServiceProvider</span>"
            "<span class=sf-dump-key>hash.driver</span>" => "<span class=sf-dump-str title="38 characters">Illuminate\Hashing\HashServiceProvider</span>"
            "<span class=sf-dump-key>mailer</span>" => "<span class=sf-dump-str title="35 characters">Illuminate\Mail\MailServiceProvider</span>"
            "<span class=sf-dump-key>swift.mailer</span>" => "<span class=sf-dump-str title="35 characters">Illuminate\Mail\MailServiceProvider</span>"
            "<span class=sf-dump-key>swift.transport</span>" => "<span class=sf-dump-str title="35 characters">Illuminate\Mail\MailServiceProvider</span>"
            "<span class=sf-dump-key>Illuminate\Mail\Markdown</span>" => "<span class=sf-dump-str title="35 characters">Illuminate\Mail\MailServiceProvider</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Pipeline\Hub</span>" => "<span class=sf-dump-str title="43 characters">Illuminate\Pipeline\PipelineServiceProvider</span>"
            "<span class=sf-dump-key>queue.worker</span>" => "<span class=sf-dump-str title="37 characters">Illuminate\Queue\QueueServiceProvider</span>"
            "<span class=sf-dump-key>queue.listener</span>" => "<span class=sf-dump-str title="37 characters">Illuminate\Queue\QueueServiceProvider</span>"
            "<span class=sf-dump-key>queue.failer</span>" => "<span class=sf-dump-str title="37 characters">Illuminate\Queue\QueueServiceProvider</span>"
            "<span class=sf-dump-key>queue.connection</span>" => "<span class=sf-dump-str title="37 characters">Illuminate\Queue\QueueServiceProvider</span>"
            "<span class=sf-dump-key>redis</span>" => "<span class=sf-dump-str title="37 characters">Illuminate\Redis\RedisServiceProvider</span>"
            "<span class=sf-dump-key>redis.connection</span>" => "<span class=sf-dump-str title="37 characters">Illuminate\Redis\RedisServiceProvider</span>"
            "<span class=sf-dump-key>auth.password</span>" => "<span class=sf-dump-str title="54 characters">Illuminate\Auth\Passwords\PasswordResetServiceProvider</span>"
            "<span class=sf-dump-key>auth.password.broker</span>" => "<span class=sf-dump-str title="54 characters">Illuminate\Auth\Passwords\PasswordResetServiceProvider</span>"
            "<span class=sf-dump-key>translator</span>" => "<span class=sf-dump-str title="49 characters">Illuminate\Translation\TranslationServiceProvider</span>"
            "<span class=sf-dump-key>translation.loader</span>" => "<span class=sf-dump-str title="49 characters">Illuminate\Translation\TranslationServiceProvider</span>"
            "<span class=sf-dump-key>validator</span>" => "<span class=sf-dump-str title="47 characters">Illuminate\Validation\ValidationServiceProvider</span>"
            "<span class=sf-dump-key>validation.presence</span>" => "<span class=sf-dump-str title="47 characters">Illuminate\Validation\ValidationServiceProvider</span>"
            "<span class=sf-dump-key>command.tinker</span>" => "<span class=sf-dump-str title="36 characters">Laravel\Tinker\TinkerServiceProvider</span>"
          </samp>]
          #<span class=sf-dump-protected title="Protected property">appPath</span>: <span class=sf-dump-const>null</span>
          #<span class=sf-dump-protected title="Protected property">databasePath</span>: <span class=sf-dump-const>null</span>
          #<span class=sf-dump-protected title="Protected property">storagePath</span>: <span class=sf-dump-const>null</span>
          #<span class=sf-dump-protected title="Protected property">environmentPath</span>: <span class=sf-dump-const>null</span>
          #<span class=sf-dump-protected title="Protected property">environmentFile</span>: "<span class=sf-dump-str title="4 characters">.env</span>"
          #<span class=sf-dump-protected title="Protected property">isRunningInConsole</span>: <span class=sf-dump-const>false</span>
          #<span class=sf-dump-protected title="Protected property">namespace</span>: <span class=sf-dump-const>null</span>
          #<span class=sf-dump-protected title="Protected property">resolved</span>: <span class=sf-dump-note>array:49</span> [<samp>
            "<span class=sf-dump-key>events</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>router</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>App\Http\Kernel</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Contracts\Http\Kernel</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Foundation\Bootstrap\LoadConfiguration</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Foundation\Bootstrap\HandleExceptions</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>env</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Foundation\Bootstrap\RegisterFacades</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Foundation\Bootstrap\RegisterProviders</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Facade\Ignition\LogRecorder\LogRecorder</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Facade\Ignition\DumpRecorder\DumpRecorder</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Facade\Ignition\QueryRecorder\QueryRecorder</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>flare.http</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>flare.client</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Facade\Ignition\Middleware\SetNotifierName</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Facade\Ignition\Middleware\AddEnvironmentInformation</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Facade\Ignition\Middleware\AddLogs</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Facade\Ignition\Middleware\AddDumps</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Facade\Ignition\Middleware\AddQueries</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Facade\IgnitionContracts\SolutionProviderRepository</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Facade\Ignition\Middleware\AddSolutions</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Foundation\Bootstrap\BootProviders</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>db.factory</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>db</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>view.engine.resolver</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>files</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>blade.compiler</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>log</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>queue</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>url</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>App\Http\Middleware\TrustProxies</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>App\Http\Middleware\CheckForMaintenanceMode</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Foundation\Http\Middleware\ValidatePostSize</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>App\Http\Middleware\TrimStrings</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Routing\Contracts\ControllerDispatcher</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>App\Http\Controllers\ProgramsController</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>encrypter</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>App\Http\Middleware\EncryptCookies</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>cookie</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>session</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Session\Middleware\StartSession</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>view.finder</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>view</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\View\Middleware\ShareErrorsFromSession</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>App\Http\Middleware\VerifyCsrfToken</span>" => <span class=sf-dump-const>true</span>
            "<span class=sf-dump-key>Illuminate\Routing\Middleware\SubstituteBindings</span>" => <span class=sf-dump-const>true</span>
          </samp>]
          #<span class=sf-dump-protected title="Protected property">bindings</span>: <span class=sf-dump-note>array:56</span> [<samp>
            "<span class=sf-dump-key>Illuminate\Foundation\Mix</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($container, $parameters = [])</span> {<a class=sf-dump-ref>#4</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Container\Container
30 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Container</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Container</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                <span class=sf-dump-meta>use</span>: {<samp>
                  <span class=sf-dump-meta>$abstract</span>: "<span class=sf-dump-str title="25 characters">Illuminate\Foundation\Mix</span>"
                  <span class=sf-dump-meta>$concrete</span>: "<span class=sf-dump-str title="25 characters">Illuminate\Foundation\Mix</span>"
                </samp>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Container\Container.php
126 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Container\Container.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">259 to 267</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>events</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#15</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Events\EventServiceProvider
38 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>EventServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Events\EventServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>EventServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref214 title="3 occurrences">#14</a> &hellip;}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Events\EventServiceProvider.php
134 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Events\EventServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">17 to 21</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>log</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#17</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Log\LogServiceProvider
33 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Log</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>LogServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Log\LogServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Log</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>LogServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref216 title="2 occurrences">#16</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Log\LogServiceProvider.php
129 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Log\LogServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">16 to 18</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>router</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#19</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Routing\RoutingServiceProvider
41 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>RoutingServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Routing\RoutingServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RoutingServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref218 title="13 occurrences">#18</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Routing\RoutingServiceProvider.php
137 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Routing\RoutingServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">45 to 47</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>url</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#20</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Routing\RoutingServiceProvider
41 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>RoutingServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Routing\RoutingServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RoutingServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref218 title="13 occurrences">#18</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Routing\RoutingServiceProvider.php
137 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Routing\RoutingServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">57 to 70</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>redirect</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#22</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Routing\RoutingServiceProvider
41 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>RoutingServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Routing\RoutingServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RoutingServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref218 title="13 occurrences">#18</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Routing\RoutingServiceProvider.php
137 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Routing\RoutingServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">114 to 125</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Psr\Http\Message\ServerRequestInterface</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#23</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Routing\RoutingServiceProvider
41 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>RoutingServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Routing\RoutingServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RoutingServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref218 title="13 occurrences">#18</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Routing\RoutingServiceProvider.php
137 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Routing\RoutingServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">135 to 148</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>false</span>
            </samp>]
            "<span class=sf-dump-key>Psr\Http\Message\ResponseInterface</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#24</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Routing\RoutingServiceProvider
41 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>RoutingServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Routing\RoutingServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RoutingServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref218 title="13 occurrences">#18</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Routing\RoutingServiceProvider.php
137 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Routing\RoutingServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">158 to 168</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>false</span>
            </samp>]
            "<span class=sf-dump-key>Illuminate\Contracts\Routing\ResponseFactory</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#25</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Routing\RoutingServiceProvider
41 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>RoutingServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Routing\RoutingServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RoutingServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref218 title="13 occurrences">#18</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Routing\RoutingServiceProvider.php
137 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Routing\RoutingServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">178 to 180</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Illuminate\Routing\Contracts\ControllerDispatcher</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#26</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Routing\RoutingServiceProvider
41 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>RoutingServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Routing\RoutingServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RoutingServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref218 title="13 occurrences">#18</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Routing\RoutingServiceProvider.php
137 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Routing\RoutingServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">190 to 192</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Illuminate\Contracts\Http\Kernel</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($container, $parameters = [])</span> {<a class=sf-dump-ref>#27</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Container\Container
30 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Container</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Container</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                <span class=sf-dump-meta>use</span>: {<samp>
                  <span class=sf-dump-meta>$abstract</span>: "<span class=sf-dump-str title="32 characters">Illuminate\Contracts\Http\Kernel</span>"
                  <span class=sf-dump-meta>$concrete</span>: "<span class=sf-dump-str title="15 characters">App\Http\Kernel</span>"
                </samp>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Container\Container.php
126 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Container\Container.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">259 to 267</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Illuminate\Contracts\Console\Kernel</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($container, $parameters = [])</span> {<a class=sf-dump-ref>#28</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Container\Container
30 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Container</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Container</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                <span class=sf-dump-meta>use</span>: {<samp>
                  <span class=sf-dump-meta>$abstract</span>: "<span class=sf-dump-str title="35 characters">Illuminate\Contracts\Console\Kernel</span>"
                  <span class=sf-dump-meta>$concrete</span>: "<span class=sf-dump-str title="18 characters">App\Console\Kernel</span>"
                </samp>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Container\Container.php
126 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Container\Container.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">259 to 267</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Illuminate\Contracts\Debug\ExceptionHandler</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($container, $parameters = [])</span> {<a class=sf-dump-ref>#29</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Container\Container
30 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Container</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Container</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                <span class=sf-dump-meta>use</span>: {<samp>
                  <span class=sf-dump-meta>$abstract</span>: "<span class=sf-dump-str title="43 characters">Illuminate\Contracts\Debug\ExceptionHandler</span>"
                  <span class=sf-dump-meta>$concrete</span>: "<span class=sf-dump-str title="22 characters">App\Exceptions\Handler</span>"
                </samp>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Container\Container.php
126 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Container\Container.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">259 to 267</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>env</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#46</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Container\Container
30 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Container</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Container</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                <span class=sf-dump-meta>use</span>: {<samp>
                  <span class=sf-dump-meta>$value</span>: "<span class=sf-dump-str title="5 characters">local</span>"
                </samp>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Container\Container.php
126 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Container\Container.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="12 characters">1257 to 1259</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>false</span>
            </samp>]
            "<span class=sf-dump-key>auth</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#43</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Auth\AuthServiceProvider
35 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>AuthServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Auth\AuthServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AuthServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref247 title="8 occurrences">#47</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Auth\AuthServiceProvider.php
131 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Auth\AuthServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">33 to 40</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>auth.driver</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#33</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Auth\AuthServiceProvider
35 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>AuthServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Auth\AuthServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AuthServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref247 title="8 occurrences">#47</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Auth\AuthServiceProvider.php
131 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Auth\AuthServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">42 to 44</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Illuminate\Contracts\Auth\Authenticatable</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#48</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Auth\AuthServiceProvider
35 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>AuthServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Auth\AuthServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AuthServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref247 title="8 occurrences">#47</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Auth\AuthServiceProvider.php
131 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Auth\AuthServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">55 to 57</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>false</span>
            </samp>]
            "<span class=sf-dump-key>Illuminate\Contracts\Auth\Access\Gate</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#45</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Auth\AuthServiceProvider
35 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>AuthServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Auth\AuthServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AuthServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref247 title="8 occurrences">#47</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Auth\AuthServiceProvider.php
131 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Auth\AuthServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">68 to 72</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>cookie</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#61</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Cookie\CookieServiceProvider
39 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Cookie</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>CookieServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Cookie\CookieServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Cookie</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>CookieServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref260 title="2 occurrences">#60</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Cookie\CookieServiceProvider.php
135 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Cookie\CookieServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">16 to 22</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>db.factory</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#63</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Database\DatabaseServiceProvider
43 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>DatabaseServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Database\DatabaseServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>DatabaseServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref262 title="7 occurrences">#62</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Database\DatabaseServiceProvider.php
139 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Database\DatabaseServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">54 to 56</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>db</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#64</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Database\DatabaseServiceProvider
43 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>DatabaseServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Database\DatabaseServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>DatabaseServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref262 title="7 occurrences">#62</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Database\DatabaseServiceProvider.php
139 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Database\DatabaseServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">61 to 63</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>db.connection</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#65</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Database\DatabaseServiceProvider
43 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>DatabaseServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Database\DatabaseServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>DatabaseServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref262 title="7 occurrences">#62</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Database\DatabaseServiceProvider.php
139 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Database\DatabaseServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">65 to 67</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>false</span>
            </samp>]
            "<span class=sf-dump-key>Faker\Generator</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#66</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Database\DatabaseServiceProvider
43 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>DatabaseServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Database\DatabaseServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>DatabaseServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref262 title="7 occurrences">#62</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Database\DatabaseServiceProvider.php
139 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Database\DatabaseServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">77 to 79</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Illuminate\Database\Eloquent\Factory</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#67</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Database\DatabaseServiceProvider
43 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>DatabaseServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Database\DatabaseServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>DatabaseServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref262 title="7 occurrences">#62</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Database\DatabaseServiceProvider.php
139 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Database\DatabaseServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">81 to 85</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Illuminate\Contracts\Queue\EntityResolver</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#68</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Database\DatabaseServiceProvider
43 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>DatabaseServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Database\DatabaseServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>DatabaseServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref262 title="7 occurrences">#62</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Database\DatabaseServiceProvider.php
139 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Database\DatabaseServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">95 to 97</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>encrypter</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#70</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Encryption\EncryptionServiceProvider
47 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Encryption</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>EncryptionServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Encryption\EncryptionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Encryption</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>EncryptionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref269 title="2 occurrences">#69</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Encryption\EncryptionServiceProvider.php
143 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Encryption\EncryptionServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">18 to 29</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>files</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#72</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Filesystem\FilesystemServiceProvider
47 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Filesystem</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>FilesystemServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Filesystem\FilesystemServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Filesystem</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>FilesystemServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref271 title="5 occurrences">#71</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Filesystem\FilesystemServiceProvider.php
143 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Filesystem\FilesystemServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">28 to 30</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>filesystem</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#73</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Filesystem\FilesystemServiceProvider
47 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Filesystem</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>FilesystemServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Filesystem\FilesystemServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Filesystem</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>FilesystemServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref271 title="5 occurrences">#71</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Filesystem\FilesystemServiceProvider.php
143 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Filesystem\FilesystemServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">58 to 60</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>filesystem.disk</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#74</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Filesystem\FilesystemServiceProvider
47 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Filesystem</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>FilesystemServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Filesystem\FilesystemServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Filesystem</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>FilesystemServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref271 title="5 occurrences">#71</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Filesystem\FilesystemServiceProvider.php
143 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Filesystem\FilesystemServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">42 to 44</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>filesystem.cloud</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#75</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Filesystem\FilesystemServiceProvider
47 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Filesystem</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>FilesystemServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Filesystem\FilesystemServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Filesystem</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>FilesystemServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref271 title="5 occurrences">#71</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Filesystem\FilesystemServiceProvider.php
143 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Filesystem\FilesystemServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">46 to 48</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Illuminate\Notifications\ChannelManager</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#82</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Notifications\NotificationServiceProvider
52 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Notifications</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>NotificationServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Notifications\NotificationServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Notifications</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>NotificationServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref281 title="3 occurrences">#81</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Notifications\NotificationServiceProvider.php
148 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Notifications\NotificationServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">34 to 36</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>session</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#88</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Session\SessionServiceProvider
41 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Session</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>SessionServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Session\SessionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Session</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>SessionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref287 title="3 occurrences">#87</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Session\SessionServiceProvider.php
137 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Session\SessionServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">31 to 33</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>session.store</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#89</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Session\SessionServiceProvider
41 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Session</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>SessionServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Session\SessionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Session</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>SessionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref287 title="3 occurrences">#87</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Session\SessionServiceProvider.php
137 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Session\SessionServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">43 to 48</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Illuminate\Session\Middleware\StartSession</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($container, $parameters = [])</span> {<a class=sf-dump-ref>#90</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Container\Container
30 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Container</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Container</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                <span class=sf-dump-meta>use</span>: {<samp>
                  <span class=sf-dump-meta>$abstract</span>: "<span class=sf-dump-str title="42 characters">Illuminate\Session\Middleware\StartSession</span>"
                  <span class=sf-dump-meta>$concrete</span>: "<span class=sf-dump-str title="42 characters">Illuminate\Session\Middleware\StartSession</span>"
                </samp>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Container\Container.php
126 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Container\Container.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">259 to 267</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>view</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#92</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\View\ViewServiceProvider
35 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\View</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>ViewServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\View\ViewServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\View</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ViewServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref291 title="6 occurrences">#91</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\View\ViewServiceProvider.php
131 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\View\ViewServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">37 to 55</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>view.finder</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#93</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\View\ViewServiceProvider
35 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\View</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>ViewServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\View\ViewServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\View</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ViewServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref291 title="6 occurrences">#91</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\View\ViewServiceProvider.php
131 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\View\ViewServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">78 to 80</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>false</span>
            </samp>]
            "<span class=sf-dump-key>blade.compiler</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#94</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\View\ViewServiceProvider
35 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\View</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>ViewServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\View\ViewServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\View</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ViewServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref291 title="6 occurrences">#91</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\View\ViewServiceProvider.php
131 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\View\ViewServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">90 to 94</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>view.engine.resolver</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#95</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\View\ViewServiceProvider
35 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\View</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>ViewServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\View\ViewServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\View</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ViewServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref291 title="6 occurrences">#91</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\View\ViewServiceProvider.php
131 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\View\ViewServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">104 to 115</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Facade\IgnitionContracts\SolutionProviderRepository</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#97</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Facade\Ignition\IgnitionServiceProvider
39 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>IgnitionServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Facade\Ignition\IgnitionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>IgnitionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref296 title="10 occurrences">#96</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\facade\ignition\src\IgnitionServiceProvider.php
117 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\facade\ignition\</span>src\IgnitionServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">162 to 166</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Facade\Ignition\ErrorPage\Renderer</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#98</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Facade\Ignition\IgnitionServiceProvider
39 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>IgnitionServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Facade\Ignition\IgnitionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>IgnitionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref296 title="10 occurrences">#96</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\facade\ignition\src\IgnitionServiceProvider.php
117 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\facade\ignition\</span>src\IgnitionServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">173 to 175</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>false</span>
            </samp>]
            "<span class=sf-dump-key>Whoops\Handler\HandlerInterface</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure(Application $app)</span> {<a class=sf-dump-ref>#99</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Facade\Ignition\IgnitionServiceProvider
39 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>IgnitionServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Facade\Ignition\IgnitionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>IgnitionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref296 title="10 occurrences">#96</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\facade\ignition\src\IgnitionServiceProvider.php
117 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\facade\ignition\</span>src\IgnitionServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">182 to 184</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>false</span>
            </samp>]
            "<span class=sf-dump-key>Facade\Ignition\IgnitionConfig</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#100</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Facade\Ignition\IgnitionServiceProvider
39 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>IgnitionServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Facade\Ignition\IgnitionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>IgnitionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref296 title="10 occurrences">#96</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\facade\ignition\src\IgnitionServiceProvider.php
117 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\facade\ignition\</span>src\IgnitionServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">191 to 203</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>flare.http</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#101</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Facade\Ignition\IgnitionServiceProvider
39 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>IgnitionServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Facade\Ignition\IgnitionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>IgnitionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref296 title="10 occurrences">#96</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\facade\ignition\src\IgnitionServiceProvider.php
117 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\facade\ignition\</span>src\IgnitionServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">210 to 216</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>flare.client</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#102</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Facade\Ignition\IgnitionServiceProvider
39 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>IgnitionServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Facade\Ignition\IgnitionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>IgnitionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref296 title="10 occurrences">#96</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\facade\ignition\src\IgnitionServiceProvider.php
117 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\facade\ignition\</span>src\IgnitionServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">220 to 226</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Facade\Ignition\LogRecorder\LogRecorder</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($container, $parameters = [])</span> {<a class=sf-dump-ref>#105</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Container\Container
30 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Container</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Container</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                <span class=sf-dump-meta>use</span>: {<samp>
                  <span class=sf-dump-meta>$abstract</span>: "<span class=sf-dump-str title="39 characters">Facade\Ignition\LogRecorder\LogRecorder</span>"
                  <span class=sf-dump-meta>$concrete</span>: "<span class=sf-dump-str title="39 characters">Facade\Ignition\LogRecorder\LogRecorder</span>"
                </samp>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Container\Container.php
126 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Container\Container.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">259 to 267</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Facade\Ignition\DumpRecorder\DumpRecorder</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($container, $parameters = [])</span> {<a class=sf-dump-ref>#103</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Container\Container
30 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Container</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Container</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                <span class=sf-dump-meta>use</span>: {<samp>
                  <span class=sf-dump-meta>$abstract</span>: "<span class=sf-dump-str title="41 characters">Facade\Ignition\DumpRecorder\DumpRecorder</span>"
                  <span class=sf-dump-meta>$concrete</span>: "<span class=sf-dump-str title="41 characters">Facade\Ignition\DumpRecorder\DumpRecorder</span>"
                </samp>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Container\Container.php
126 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Container\Container.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">259 to 267</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Facade\Ignition\QueryRecorder\QueryRecorder</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($container, $parameters = [])</span> {<a class=sf-dump-ref>#108</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Container\Container
30 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Container</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Container</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                <span class=sf-dump-meta>use</span>: {<samp>
                  <span class=sf-dump-meta>$abstract</span>: "<span class=sf-dump-str title="43 characters">Facade\Ignition\QueryRecorder\QueryRecorder</span>"
                  <span class=sf-dump-meta>$concrete</span>: "<span class=sf-dump-str title="43 characters">Facade\Ignition\QueryRecorder\QueryRecorder</span>"
                </samp>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Container\Container.php
126 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Container\Container.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">259 to 267</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>flare.logger</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#139</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Facade\Ignition\IgnitionServiceProvider
39 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>IgnitionServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Facade\Ignition\IgnitionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>IgnitionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref296 title="10 occurrences">#96</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\facade\ignition\src\IgnitionServiceProvider.php
117 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\facade\ignition\</span>src\IgnitionServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">235 to 248</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>command.flare:test</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($container, $parameters = [])</span> {<a class=sf-dump-ref>#149</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Container\Container
30 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Container</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Container</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                <span class=sf-dump-meta>use</span>: {<samp>
                  <span class=sf-dump-meta>$abstract</span>: "<span class=sf-dump-str title="18 characters">command.flare:test</span>"
                  <span class=sf-dump-meta>$concrete</span>: "<span class=sf-dump-str title="36 characters">Facade\Ignition\Commands\TestCommand</span>"
                </samp>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Container\Container.php
126 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Container\Container.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">259 to 267</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>false</span>
            </samp>]
            "<span class=sf-dump-key>command.make:solution</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($container, $parameters = [])</span> {<a class=sf-dump-ref>#151</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Container\Container
30 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Container</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Container</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                <span class=sf-dump-meta>use</span>: {<samp>
                  <span class=sf-dump-meta>$abstract</span>: "<span class=sf-dump-str title="21 characters">command.make:solution</span>"
                  <span class=sf-dump-meta>$concrete</span>: "<span class=sf-dump-str title="44 characters">Facade\Ignition\Commands\SolutionMakeCommand</span>"
                </samp>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Container\Container.php
126 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Container\Container.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">259 to 267</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>false</span>
            </samp>]
            "<span class=sf-dump-key>queue</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#153</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Queue\QueueServiceProvider
37 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>QueueServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Queue\QueueServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueueServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2152 title="13 occurrences">#152</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Queue\QueueServiceProvider.php
133 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Queue\QueueServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">46 to 53</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>queue.connection</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#154</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Queue\QueueServiceProvider
37 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>QueueServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Queue\QueueServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueueServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2152 title="13 occurrences">#152</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Queue\QueueServiceProvider.php
133 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Queue\QueueServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">63 to 65</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>queue.worker</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#155</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Queue\QueueServiceProvider
37 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>QueueServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Queue\QueueServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueueServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2152 title="13 occurrences">#152</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Queue\QueueServiceProvider.php
133 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Queue\QueueServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">166 to 177</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>queue.listener</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#156</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Queue\QueueServiceProvider
37 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>QueueServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Queue\QueueServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueueServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2152 title="13 occurrences">#152</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Queue\QueueServiceProvider.php
133 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Queue\QueueServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">187 to 189</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>queue.failer</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#157</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Queue\QueueServiceProvider
37 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>QueueServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Queue\QueueServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueueServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2152 title="13 occurrences">#152</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Queue\QueueServiceProvider.php
133 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Queue\QueueServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">199 to 209</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
            "<span class=sf-dump-key>Facade\Ignition\DumpRecorder\MultiDumpHandler</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              "<span class=sf-dump-key>concrete</span>" => <span class=sf-dump-note>Closure($container, $parameters = [])</span> {<a class=sf-dump-ref>#172</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Container\Container
30 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Container</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Container</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                <span class=sf-dump-meta>use</span>: {<samp>
                  <span class=sf-dump-meta>$abstract</span>: "<span class=sf-dump-str title="45 characters">Facade\Ignition\DumpRecorder\MultiDumpHandler</span>"
                  <span class=sf-dump-meta>$concrete</span>: <span class=sf-dump-note title="Facade\Ignition\DumpRecorder\MultiDumpHandler
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\DumpRecorder</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>MultiDumpHandler</span> {<a class=sf-dump-ref>#171</a> &hellip;}
                </samp>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Container\Container.php
126 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Container\Container.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">259 to 267</span>"
              </samp>}
              "<span class=sf-dump-key>shared</span>" => <span class=sf-dump-const>true</span>
            </samp>]
          </samp>]
          #<span class=sf-dump-protected title="Protected property">methodBindings</span>: []
          #<span class=sf-dump-protected title="Protected property">instances</span>: <span class=sf-dump-note>array:40</span> [<samp>
            "<span class=sf-dump-key>path</span>" => "<span class=sf-dump-str title="66 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\app</span>"
            "<span class=sf-dump-key>path.base</span>" => "<span class=sf-dump-str title="62 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app</span>"
            "<span class=sf-dump-key>path.lang</span>" => "<span class=sf-dump-str title="77 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\resources\lang</span>"
            "<span class=sf-dump-key>path.config</span>" => "<span class=sf-dump-str title="69 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\config</span>"
            "<span class=sf-dump-key>path.public</span>" => "<span class=sf-dump-str title="69 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\public</span>"
            "<span class=sf-dump-key>path.storage</span>" => "<span class=sf-dump-str title="70 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\storage</span>"
            "<span class=sf-dump-key>path.database</span>" => "<span class=sf-dump-str title="71 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\database</span>"
            "<span class=sf-dump-key>path.resources</span>" => "<span class=sf-dump-str title="72 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\resources</span>"
            "<span class=sf-dump-key>path.bootstrap</span>" => "<span class=sf-dump-str title="72 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\bootstrap</span>"
            "<span class=sf-dump-key>app</span>" => <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            "<span class=sf-dump-key>Illuminate\Container\Container</span>" => <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            "<span class=sf-dump-key>Illuminate\Foundation\PackageManifest</span>" => <span class=sf-dump-note title="Illuminate\Foundation\PackageManifest
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>PackageManifest</span> {<a class=sf-dump-ref>#5</a><samp>
              +<span class=sf-dump-public title="Public property">files</span>: <span class=sf-dump-note title="Illuminate\Filesystem\Filesystem
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Filesystem</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Filesystem</span> {<a class=sf-dump-ref>#6</a>}
              +<span class=sf-dump-public title="Public property">basePath</span>: "<span class=sf-dump-str title="62 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app</span>"
              +<span class=sf-dump-public title="Public property">vendorPath</span>: "<span class=sf-dump-str title="69 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app/vendor</span>"
              +<span class=sf-dump-public title="Public property">manifestPath</span>: "<span class=sf-dump-str title="91 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\bootstrap\cache/packages.php</span>"
              +<span class=sf-dump-public title="Public property">manifest</span>: <span class=sf-dump-note>array:5</span> [<samp>
                "<span class=sf-dump-key>facade/ignition</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                  "<span class=sf-dump-key>providers</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                    <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="39 characters">Facade\Ignition\IgnitionServiceProvider</span>"
                  </samp>]
                  "<span class=sf-dump-key>aliases</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                    "<span class=sf-dump-key>Flare</span>" => "<span class=sf-dump-str title="29 characters">Facade\Ignition\Facades\Flare</span>"
                  </samp>]
                </samp>]
                "<span class=sf-dump-key>fideloper/proxy</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                  "<span class=sf-dump-key>providers</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                    <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="43 characters">Fideloper\Proxy\TrustedProxyServiceProvider</span>"
                  </samp>]
                </samp>]
                "<span class=sf-dump-key>laravel/tinker</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                  "<span class=sf-dump-key>providers</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                    <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="36 characters">Laravel\Tinker\TinkerServiceProvider</span>"
                  </samp>]
                </samp>]
                "<span class=sf-dump-key>nesbot/carbon</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                  "<span class=sf-dump-key>providers</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                    <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="30 characters">Carbon\Laravel\ServiceProvider</span>"
                  </samp>]
                </samp>]
                "<span class=sf-dump-key>nunomaduro/collision</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                  "<span class=sf-dump-key>providers</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                    <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="62 characters">NunoMaduro\Collision\Adapters\Laravel\CollisionServiceProvider</span>"
                  </samp>]
                </samp>]
              </samp>]
            </samp>}
            "<span class=sf-dump-key>events</span>" => <span class=sf-dump-note title="Illuminate\Events\Dispatcher
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Dispatcher</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref235 title="9 occurrences">#35</a>}
            "<span class=sf-dump-key>router</span>" => <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a><samp id=sf-dump-1538836868-ref234>
              #<span class=sf-dump-protected title="Protected property">events</span>: <span class=sf-dump-note title="Illuminate\Events\Dispatcher
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Dispatcher</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref235 title="9 occurrences">#35</a>}
              #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
              #<span class=sf-dump-protected title="Protected property">routes</span>: <span class=sf-dump-note title="Illuminate\Routing\RouteCollection
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RouteCollection</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref237 title="3 occurrences">#37</a><samp id=sf-dump-1538836868-ref237>
                #<span class=sf-dump-protected title="Protected property">routes</span>: <span class=sf-dump-note>array:3</span> [<samp>
                  "<span class=sf-dump-key>GET</span>" => <span class=sf-dump-note>array:11</span> [<samp>
                    "<span class=sf-dump-key>_ignition/health-check</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2141 title="5 occurrences">#141</a><samp id=sf-dump-1538836868-ref2141>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="22 characters">_ignition/health-check</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:2</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">GET</span>"
                        <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="4 characters">HEAD</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:7</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        "<span class=sf-dump-key>uses</span>" => "<span class=sf-dump-str title="63 characters">Facade\Ignition\Http\Controllers\HealthCheckController@__invoke</span>"
                        "<span class=sf-dump-key>controller</span>" => "<span class=sf-dump-str title="54 characters">Facade\Ignition\Http\Controllers\HealthCheckController</span>"
                        "<span class=sf-dump-key>as</span>" => "<span class=sf-dump-str title="20 characters">ignition.healthCheck</span>"
                        "<span class=sf-dump-key>namespace</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>prefix</span>" => "<span class=sf-dump-str title="9 characters">_ignition</span>"
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                    "<span class=sf-dump-key>_ignition/scripts/{script}</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2146 title="5 occurrences">#146</a><samp id=sf-dump-1538836868-ref2146>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="26 characters">_ignition/scripts/{script}</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:2</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">GET</span>"
                        <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="4 characters">HEAD</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:7</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        "<span class=sf-dump-key>uses</span>" => "<span class=sf-dump-str title="58 characters">Facade\Ignition\Http\Controllers\ScriptController@__invoke</span>"
                        "<span class=sf-dump-key>controller</span>" => "<span class=sf-dump-str title="49 characters">Facade\Ignition\Http\Controllers\ScriptController</span>"
                        "<span class=sf-dump-key>as</span>" => "<span class=sf-dump-str title="16 characters">ignition.scripts</span>"
                        "<span class=sf-dump-key>namespace</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>prefix</span>" => "<span class=sf-dump-str title="9 characters">_ignition</span>"
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                    "<span class=sf-dump-key>_ignition/styles/{style}</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2147 title="5 occurrences">#147</a><samp id=sf-dump-1538836868-ref2147>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="24 characters">_ignition/styles/{style}</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:2</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">GET</span>"
                        <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="4 characters">HEAD</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:7</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        "<span class=sf-dump-key>uses</span>" => "<span class=sf-dump-str title="57 characters">Facade\Ignition\Http\Controllers\StyleController@__invoke</span>"
                        "<span class=sf-dump-key>controller</span>" => "<span class=sf-dump-str title="48 characters">Facade\Ignition\Http\Controllers\StyleController</span>"
                        "<span class=sf-dump-key>as</span>" => "<span class=sf-dump-str title="15 characters">ignition.styles</span>"
                        "<span class=sf-dump-key>namespace</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>prefix</span>" => "<span class=sf-dump-str title="9 characters">_ignition</span>"
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                    "<span class=sf-dump-key>api/user</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2196 title="3 occurrences">#196</a><samp id=sf-dump-1538836868-ref2196>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="8 characters">api/user</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:2</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">GET</span>"
                        <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="4 characters">HEAD</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:5</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:2</span> [ &hellip;2]
                        "<span class=sf-dump-key>uses</span>" => <span class=sf-dump-note>Closure(Request $request)</span> {<a class=sf-dump-ref>#195</a> &hellip;4}
                        "<span class=sf-dump-key>namespace</span>" => "<span class=sf-dump-str title="20 characters">App\Http\Controllers</span>"
                        "<span class=sf-dump-key>prefix</span>" => "<span class=sf-dump-str title="3 characters">api</span>"
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                    "<span class=sf-dump-key>/</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2198 title="3 occurrences">#198</a><samp id=sf-dump-1538836868-ref2198>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str>/</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:2</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">GET</span>"
                        <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="4 characters">HEAD</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:5</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        "<span class=sf-dump-key>uses</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#197</a> &hellip;4}
                        "<span class=sf-dump-key>namespace</span>" => "<span class=sf-dump-str title="20 characters">App\Http\Controllers</span>"
                        "<span class=sf-dump-key>prefix</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                    "<span class=sf-dump-key>main</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2200 title="3 occurrences">#200</a><samp id=sf-dump-1538836868-ref2200>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="4 characters">main</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:2</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">GET</span>"
                        <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="4 characters">HEAD</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:5</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        "<span class=sf-dump-key>uses</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#199</a> &hellip;4}
                        "<span class=sf-dump-key>namespace</span>" => "<span class=sf-dump-str title="20 characters">App\Http\Controllers</span>"
                        "<span class=sf-dump-key>prefix</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                    "<span class=sf-dump-key>stats</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2202 title="3 occurrences">#202</a><samp id=sf-dump-1538836868-ref2202>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="5 characters">stats</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:2</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">GET</span>"
                        <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="4 characters">HEAD</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:5</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        "<span class=sf-dump-key>uses</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#201</a> &hellip;4}
                        "<span class=sf-dump-key>namespace</span>" => "<span class=sf-dump-str title="20 characters">App\Http\Controllers</span>"
                        "<span class=sf-dump-key>prefix</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                    "<span class=sf-dump-key>program</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2204 title="3 occurrences">#204</a><samp id=sf-dump-1538836868-ref2204>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="7 characters">program</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:2</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">GET</span>"
                        <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="4 characters">HEAD</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:5</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        "<span class=sf-dump-key>uses</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#203</a> &hellip;4}
                        "<span class=sf-dump-key>namespace</span>" => "<span class=sf-dump-str title="20 characters">App\Http\Controllers</span>"
                        "<span class=sf-dump-key>prefix</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                    "<span class=sf-dump-key>programs/{data}</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2205 title="4 occurrences">#205</a><samp id=sf-dump-1538836868-ref2205>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="15 characters">programs/{data}</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:2</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">GET</span>"
                        <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="4 characters">HEAD</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:6</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        "<span class=sf-dump-key>uses</span>" => "<span class=sf-dump-str title="51 characters">App\Http\Controllers\ProgramsController@getPrograms</span>"
                        "<span class=sf-dump-key>controller</span>" => "<span class=sf-dump-str title="51 characters">App\Http\Controllers\ProgramsController@getPrograms</span>"
                        "<span class=sf-dump-key>namespace</span>" => "<span class=sf-dump-str title="20 characters">App\Http\Controllers</span>"
                        "<span class=sf-dump-key>prefix</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                    "<span class=sf-dump-key>test</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2210 title="3 occurrences">#210</a><samp id=sf-dump-1538836868-ref2210>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="4 characters">test</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:2</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">GET</span>"
                        <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="4 characters">HEAD</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:5</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        "<span class=sf-dump-key>uses</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#209</a> &hellip;4}
                        "<span class=sf-dump-key>namespace</span>" => "<span class=sf-dump-str title="20 characters">App\Http\Controllers</span>"
                        "<span class=sf-dump-key>prefix</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                    "<span class=sf-dump-key>debug</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2212 title="3 occurrences">#212</a><samp id=sf-dump-1538836868-ref2212>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="5 characters">debug</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:2</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">GET</span>"
                        <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="4 characters">HEAD</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:5</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        "<span class=sf-dump-key>uses</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#211</a> &hellip;4}
                        "<span class=sf-dump-key>namespace</span>" => "<span class=sf-dump-str title="20 characters">App\Http\Controllers</span>"
                        "<span class=sf-dump-key>prefix</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                  </samp>]
                  "<span class=sf-dump-key>HEAD</span>" => <span class=sf-dump-note>array:11</span> [<samp>
                    "<span class=sf-dump-key>_ignition/health-check</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2141 title="5 occurrences">#141</a>}
                    "<span class=sf-dump-key>_ignition/scripts/{script}</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2146 title="5 occurrences">#146</a>}
                    "<span class=sf-dump-key>_ignition/styles/{style}</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2147 title="5 occurrences">#147</a>}
                    "<span class=sf-dump-key>api/user</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2196 title="3 occurrences">#196</a>}
                    "<span class=sf-dump-key>/</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2198 title="3 occurrences">#198</a>}
                    "<span class=sf-dump-key>main</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2200 title="3 occurrences">#200</a>}
                    "<span class=sf-dump-key>stats</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2202 title="3 occurrences">#202</a>}
                    "<span class=sf-dump-key>program</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2204 title="3 occurrences">#204</a>}
                    "<span class=sf-dump-key>programs/{data}</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2205 title="4 occurrences">#205</a>}
                    "<span class=sf-dump-key>test</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2210 title="3 occurrences">#210</a>}
                    "<span class=sf-dump-key>debug</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2212 title="3 occurrences">#212</a>}
                  </samp>]
                  "<span class=sf-dump-key>POST</span>" => <span class=sf-dump-note>array:5</span> [<samp>
                    "<span class=sf-dump-key>_ignition/execute-solution</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2140 title="4 occurrences">#140</a><samp id=sf-dump-1538836868-ref2140>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="26 characters">_ignition/execute-solution</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:1</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="4 characters">POST</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:7</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:2</span> [ &hellip;2]
                        "<span class=sf-dump-key>uses</span>" => "<span class=sf-dump-str title="67 characters">Facade\Ignition\Http\Controllers\ExecuteSolutionController@__invoke</span>"
                        "<span class=sf-dump-key>controller</span>" => "<span class=sf-dump-str title="58 characters">Facade\Ignition\Http\Controllers\ExecuteSolutionController</span>"
                        "<span class=sf-dump-key>as</span>" => "<span class=sf-dump-str title="24 characters">ignition.executeSolution</span>"
                        "<span class=sf-dump-key>namespace</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>prefix</span>" => "<span class=sf-dump-str title="9 characters">_ignition</span>"
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-note title="Symfony\Component\Routing\CompiledRoute
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Symfony\Component\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>CompiledRoute</span> {<a class=sf-dump-ref>#229</a><samp>
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">variables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">tokens</span>: <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">staticPrefix</span>: "<span class=sf-dump-str title="27 characters">/_ignition/execute-solution</span>"
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">regex</span>: "<span class=sf-dump-str title="35 characters">#^/_ignition/execute\-solution$#sDu</span>"
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">pathVariables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostVariables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostRegex</span>: <span class=sf-dump-const>null</span>
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostTokens</span>: []
                      </samp>}
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                    "<span class=sf-dump-key>_ignition/share-report</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2145 title="4 occurrences">#145</a><samp id=sf-dump-1538836868-ref2145>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="22 characters">_ignition/share-report</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:1</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="4 characters">POST</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:7</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:2</span> [ &hellip;2]
                        "<span class=sf-dump-key>uses</span>" => "<span class=sf-dump-str title="63 characters">Facade\Ignition\Http\Controllers\ShareReportController@__invoke</span>"
                        "<span class=sf-dump-key>controller</span>" => "<span class=sf-dump-str title="54 characters">Facade\Ignition\Http\Controllers\ShareReportController</span>"
                        "<span class=sf-dump-key>as</span>" => "<span class=sf-dump-str title="20 characters">ignition.shareReport</span>"
                        "<span class=sf-dump-key>namespace</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>prefix</span>" => "<span class=sf-dump-str title="9 characters">_ignition</span>"
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-note title="Symfony\Component\Routing\CompiledRoute
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Symfony\Component\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>CompiledRoute</span> {<a class=sf-dump-ref>#234</a><samp>
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">variables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">tokens</span>: <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">staticPrefix</span>: "<span class=sf-dump-str title="23 characters">/_ignition/share-report</span>"
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">regex</span>: "<span class=sf-dump-str title="31 characters">#^/_ignition/share\-report$#sDu</span>"
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">pathVariables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostVariables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostRegex</span>: <span class=sf-dump-const>null</span>
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostTokens</span>: []
                      </samp>}
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                    "<span class=sf-dump-key>saveProgram</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2206 title="3 occurrences">#206</a><samp id=sf-dump-1538836868-ref2206>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="11 characters">saveProgram</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:1</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="4 characters">POST</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:6</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        "<span class=sf-dump-key>uses</span>" => "<span class=sf-dump-str title="51 characters">App\Http\Controllers\ProgramsController@saveProgram</span>"
                        "<span class=sf-dump-key>controller</span>" => "<span class=sf-dump-str title="51 characters">App\Http\Controllers\ProgramsController@saveProgram</span>"
                        "<span class=sf-dump-key>namespace</span>" => "<span class=sf-dump-str title="20 characters">App\Http\Controllers</span>"
                        "<span class=sf-dump-key>prefix</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-note title="Symfony\Component\Routing\CompiledRoute
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Symfony\Component\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>CompiledRoute</span> {<a class=sf-dump-ref>#235</a><samp>
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">variables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">tokens</span>: <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">staticPrefix</span>: "<span class=sf-dump-str title="12 characters">/saveProgram</span>"
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">regex</span>: "<span class=sf-dump-str title="19 characters">#^/saveProgram$#sDu</span>"
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">pathVariables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostVariables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostRegex</span>: <span class=sf-dump-const>null</span>
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostTokens</span>: []
                      </samp>}
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                    "<span class=sf-dump-key>deleteProgram</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2207 title="3 occurrences">#207</a><samp id=sf-dump-1538836868-ref2207>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="13 characters">deleteProgram</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:1</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="4 characters">POST</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:6</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        "<span class=sf-dump-key>uses</span>" => "<span class=sf-dump-str title="53 characters">App\Http\Controllers\ProgramsController@deleteProgram</span>"
                        "<span class=sf-dump-key>controller</span>" => "<span class=sf-dump-str title="53 characters">App\Http\Controllers\ProgramsController@deleteProgram</span>"
                        "<span class=sf-dump-key>namespace</span>" => "<span class=sf-dump-str title="20 characters">App\Http\Controllers</span>"
                        "<span class=sf-dump-key>prefix</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: <span class=sf-dump-const>null</span>
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-const>null</span>
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-note title="Symfony\Component\Routing\CompiledRoute
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Symfony\Component\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>CompiledRoute</span> {<a class=sf-dump-ref>#236</a><samp>
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">variables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">tokens</span>: <span class=sf-dump-note>array:1</span> [ &hellip;1]
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">staticPrefix</span>: "<span class=sf-dump-str title="14 characters">/deleteProgram</span>"
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">regex</span>: "<span class=sf-dump-str title="21 characters">#^/deleteProgram$#sDu</span>"
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">pathVariables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostVariables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostRegex</span>: <span class=sf-dump-const>null</span>
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostTokens</span>: []
                      </samp>}
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                    "<span class=sf-dump-key>newProgram</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2208 title="6 occurrences">#208</a><samp id=sf-dump-1538836868-ref2208>
                      +<span class=sf-dump-public title="Public property">uri</span>: "<span class=sf-dump-str title="10 characters">newProgram</span>"
                      +<span class=sf-dump-public title="Public property">methods</span>: <span class=sf-dump-note>array:1</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="4 characters">POST</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">action</span>: <span class=sf-dump-note>array:6</span> [<samp>
                        "<span class=sf-dump-key>middleware</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                          <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">web</span>"
                        </samp>]
                        "<span class=sf-dump-key>uses</span>" => "<span class=sf-dump-str title="50 characters">App\Http\Controllers\ProgramsController@newProgram</span>"
                        "<span class=sf-dump-key>controller</span>" => "<span class=sf-dump-str title="50 characters">App\Http\Controllers\ProgramsController@newProgram</span>"
                        "<span class=sf-dump-key>namespace</span>" => "<span class=sf-dump-str title="20 characters">App\Http\Controllers</span>"
                        "<span class=sf-dump-key>prefix</span>" => <span class=sf-dump-const>null</span>
                        "<span class=sf-dump-key>where</span>" => []
                      </samp>]
                      +<span class=sf-dump-public title="Public property">isFallback</span>: <span class=sf-dump-const>false</span>
                      +<span class=sf-dump-public title="Public property">controller</span>: <span class=sf-dump-note title="App\Http\Controllers\ProgramsController
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">App\Http\Controllers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ProgramsController</span> {<a class=sf-dump-ref>#224</a><samp>
                        #<span class=sf-dump-protected title="Protected property">middleware</span>: []
                      </samp>}
                      +<span class=sf-dump-public title="Public property">defaults</span>: []
                      +<span class=sf-dump-public title="Public property">wheres</span>: []
                      +<span class=sf-dump-public title="Public property">parameters</span>: []
                      +<span class=sf-dump-public title="Public property">parameterNames</span>: []
                      #<span class=sf-dump-protected title="Protected property">originalParameters</span>: []
                      +<span class=sf-dump-public title="Public property">computedMiddleware</span>: <span class=sf-dump-note>array:1</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">web</span>"
                      </samp>]
                      +<span class=sf-dump-public title="Public property">compiled</span>: <span class=sf-dump-note title="Symfony\Component\Routing\CompiledRoute
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Symfony\Component\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>CompiledRoute</span> {<a class=sf-dump-ref>#237</a><samp>
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">variables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">tokens</span>: <span class=sf-dump-note>array:1</span> [<samp>
                          <span class=sf-dump-index>0</span> => <span class=sf-dump-note>array:2</span> [<samp>
                            <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="4 characters">text</span>"
                            <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="11 characters">/newProgram</span>"
                          </samp>]
                        </samp>]
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">staticPrefix</span>: "<span class=sf-dump-str title="11 characters">/newProgram</span>"
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">regex</span>: "<span class=sf-dump-str title="18 characters">#^/newProgram$#sDu</span>"
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">pathVariables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostVariables</span>: []
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostRegex</span>: <span class=sf-dump-const>null</span>
                        -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\Routing\CompiledRoute`">hostTokens</span>: []
                      </samp>}
                      #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                      #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                    </samp>}
                  </samp>]
                </samp>]
                #<span class=sf-dump-protected title="Protected property">allRoutes</span>: <span class=sf-dump-note>array:16</span> [<samp>
                  "<span class=sf-dump-key>HEAD_ignition/health-check</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2141 title="5 occurrences">#141</a>}
                  "<span class=sf-dump-key>POST_ignition/execute-solution</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2140 title="4 occurrences">#140</a>}
                  "<span class=sf-dump-key>POST_ignition/share-report</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2145 title="4 occurrences">#145</a>}
                  "<span class=sf-dump-key>HEAD_ignition/scripts/{script}</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2146 title="5 occurrences">#146</a>}
                  "<span class=sf-dump-key>HEAD_ignition/styles/{style}</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2147 title="5 occurrences">#147</a>}
                  "<span class=sf-dump-key>HEADapi/user</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2196 title="3 occurrences">#196</a>}
                  "<span class=sf-dump-key>HEAD/</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2198 title="3 occurrences">#198</a>}
                  "<span class=sf-dump-key>HEADmain</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2200 title="3 occurrences">#200</a>}
                  "<span class=sf-dump-key>HEADstats</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2202 title="3 occurrences">#202</a>}
                  "<span class=sf-dump-key>HEADprogram</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2204 title="3 occurrences">#204</a>}
                  "<span class=sf-dump-key>HEADprograms/{data}</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2205 title="4 occurrences">#205</a>}
                  "<span class=sf-dump-key>POSTsaveProgram</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2206 title="3 occurrences">#206</a>}
                  "<span class=sf-dump-key>POSTdeleteProgram</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2207 title="3 occurrences">#207</a>}
                  "<span class=sf-dump-key>POSTnewProgram</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2208 title="6 occurrences">#208</a>}
                  "<span class=sf-dump-key>HEADtest</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2210 title="3 occurrences">#210</a>}
                  "<span class=sf-dump-key>HEADdebug</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2212 title="3 occurrences">#212</a>}
                </samp>]
                #<span class=sf-dump-protected title="Protected property">nameList</span>: <span class=sf-dump-note>array:5</span> [<samp>
                  "<span class=sf-dump-key>ignition.healthCheck</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2141 title="5 occurrences">#141</a>}
                  "<span class=sf-dump-key>ignition.executeSolution</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2140 title="4 occurrences">#140</a>}
                  "<span class=sf-dump-key>ignition.shareReport</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2145 title="4 occurrences">#145</a>}
                  "<span class=sf-dump-key>ignition.scripts</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2146 title="5 occurrences">#146</a>}
                  "<span class=sf-dump-key>ignition.styles</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2147 title="5 occurrences">#147</a>}
                </samp>]
                #<span class=sf-dump-protected title="Protected property">actionList</span>: <span class=sf-dump-note>array:9</span> [<samp>
                  "<span class=sf-dump-key>Facade\Ignition\Http\Controllers\HealthCheckController</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2141 title="5 occurrences">#141</a>}
                  "<span class=sf-dump-key>Facade\Ignition\Http\Controllers\ExecuteSolutionController</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2140 title="4 occurrences">#140</a>}
                  "<span class=sf-dump-key>Facade\Ignition\Http\Controllers\ShareReportController</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2145 title="4 occurrences">#145</a>}
                  "<span class=sf-dump-key>Facade\Ignition\Http\Controllers\ScriptController</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2146 title="5 occurrences">#146</a>}
                  "<span class=sf-dump-key>Facade\Ignition\Http\Controllers\StyleController</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2147 title="5 occurrences">#147</a>}
                  "<span class=sf-dump-key>App\Http\Controllers\ProgramsController@getPrograms</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2205 title="4 occurrences">#205</a>}
                  "<span class=sf-dump-key>App\Http\Controllers\ProgramsController@saveProgram</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2206 title="3 occurrences">#206</a>}
                  "<span class=sf-dump-key>App\Http\Controllers\ProgramsController@deleteProgram</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2207 title="3 occurrences">#207</a>}
                  "<span class=sf-dump-key>App\Http\Controllers\ProgramsController@newProgram</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2208 title="6 occurrences">#208</a>}
                </samp>]
              </samp>}
              #<span class=sf-dump-protected title="Protected property">current</span>: <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2208 title="6 occurrences">#208</a>}
              #<span class=sf-dump-protected title="Protected property">currentRequest</span>: <span class=sf-dump-note title="Illuminate\Http\Request
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Http</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Request</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref251 title="3 occurrences">#51</a><samp id=sf-dump-1538836868-ref251>
                #<span class=sf-dump-protected title="Protected property">json</span>: <span class=sf-dump-const>null</span>
                #<span class=sf-dump-protected title="Protected property">convertedFiles</span>: []
                #<span class=sf-dump-protected title="Protected property">userResolver</span>: <span class=sf-dump-note>Closure($guard = null)</span> {<a class=sf-dump-ref>#31</a><samp>
                  <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Auth\AuthServiceProvider
35 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>AuthServiceProvider</span>"
                  <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Auth\AuthServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AuthServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref247 title="8 occurrences">#47</a>}
                  <span class=sf-dump-meta>use</span>: {<samp>
                    <span class=sf-dump-meta>$app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                  </samp>}
                  <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Auth\AuthServiceProvider.php
131 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Auth\AuthServiceProvider.php</span>"
                  <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">83 to 85</span>"
                </samp>}
                #<span class=sf-dump-protected title="Protected property">routeResolver</span>: <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#225</a><samp>
                  <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Routing\Router
25 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Router</span>"
                  <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
                  <span class=sf-dump-meta>use</span>: {<samp>
                    <span class=sf-dump-meta>$route</span>: <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2208 title="6 occurrences">#208</a>}
                  </samp>}
                  <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Routing\Router.php
121 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Routing\Router.php</span>"
                  <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">651 to 653</span>"
                </samp>}
                +<span class=sf-dump-public title="Public property">attributes</span>: <span class=sf-dump-note title="Symfony\Component\HttpFoundation\ParameterBag
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Symfony\Component\HttpFoundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ParameterBag</span> {<a class=sf-dump-ref>#53</a><samp>
                  #<span class=sf-dump-protected title="Protected property">parameters</span>: []
                </samp>}
                +<span class=sf-dump-public title="Public property">request</span>: <span class=sf-dump-note title="Symfony\Component\HttpFoundation\ParameterBag
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Symfony\Component\HttpFoundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ParameterBag</span> {<a class=sf-dump-ref>#52</a><samp>
                  #<span class=sf-dump-protected title="Protected property">parameters</span>: <span class=sf-dump-note>array:5</span> [<samp>
                    "<span class=sf-dump-key>_token</span>" => "<span class=sf-dump-str title="40 characters">q8idTlCCf8UGBB8y4tDc60OII3waa3DWUL7DVeyq</span>"
                    "<span class=sf-dump-key>program</span>" => "<span class=sf-dump-str title="2 characters">14</span>"
                    "<span class=sf-dump-key>device_id</span>" => "<span class=sf-dump-str>1</span>"
                    "<span class=sf-dump-key>program_id</span>" => "<span class=sf-dump-str title="2 characters">14</span>"
                    "<span class=sf-dump-key>program_name</span>" => "<span class=sf-dump-str title="6 characters">guille</span>"
                  </samp>]
                </samp>}
                +<span class=sf-dump-public title="Public property">query</span>: <span class=sf-dump-note title="Symfony\Component\HttpFoundation\ParameterBag
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Symfony\Component\HttpFoundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ParameterBag</span> {<a class=sf-dump-ref>#59</a><samp>
                  #<span class=sf-dump-protected title="Protected property">parameters</span>: []
                </samp>}
                +<span class=sf-dump-public title="Public property">server</span>: <span class=sf-dump-note title="Symfony\Component\HttpFoundation\ServerBag
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Symfony\Component\HttpFoundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ServerBag</span> {<a class=sf-dump-ref>#55</a><samp>
                  #<span class=sf-dump-protected title="Protected property">parameters</span>: <span class=sf-dump-note>array:31</span> [<samp>
                    "<span class=sf-dump-key>DOCUMENT_ROOT</span>" => "<span class=sf-dump-str title="69 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\public</span>"
                    "<span class=sf-dump-key>REMOTE_ADDR</span>" => "<span class=sf-dump-str title="9 characters">127.0.0.1</span>"
                    "<span class=sf-dump-key>REMOTE_PORT</span>" => "<span class=sf-dump-str title="5 characters">52964</span>"
                    "<span class=sf-dump-key>SERVER_SOFTWARE</span>" => "<span class=sf-dump-str title="28 characters">PHP 7.3.7 Development Server</span>"
                    "<span class=sf-dump-key>SERVER_PROTOCOL</span>" => "<span class=sf-dump-str title="8 characters">HTTP/1.1</span>"
                    "<span class=sf-dump-key>SERVER_NAME</span>" => "<span class=sf-dump-str title="9 characters">127.0.0.1</span>"
                    "<span class=sf-dump-key>SERVER_PORT</span>" => "<span class=sf-dump-str title="4 characters">8000</span>"
                    "<span class=sf-dump-key>REQUEST_URI</span>" => "<span class=sf-dump-str title="11 characters">/newProgram</span>"
                    "<span class=sf-dump-key>REQUEST_METHOD</span>" => "<span class=sf-dump-str title="4 characters">POST</span>"
                    "<span class=sf-dump-key>SCRIPT_NAME</span>" => "<span class=sf-dump-str title="10 characters">/index.php</span>"
                    "<span class=sf-dump-key>SCRIPT_FILENAME</span>" => "<span class=sf-dump-str title="79 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\public\index.php</span>"
                    "<span class=sf-dump-key>PATH_INFO</span>" => "<span class=sf-dump-str title="11 characters">/newProgram</span>"
                    "<span class=sf-dump-key>PHP_SELF</span>" => "<span class=sf-dump-str title="21 characters">/index.php/newProgram</span>"
                    "<span class=sf-dump-key>HTTP_HOST</span>" => "<span class=sf-dump-str title="14 characters">localhost:8000</span>"
                    "<span class=sf-dump-key>HTTP_CONNECTION</span>" => "<span class=sf-dump-str title="10 characters">keep-alive</span>"
                    "<span class=sf-dump-key>CONTENT_LENGTH</span>" => "<span class=sf-dump-str title="3 characters">104</span>"
                    "<span class=sf-dump-key>HTTP_CONTENT_LENGTH</span>" => "<span class=sf-dump-str title="3 characters">104</span>"
                    "<span class=sf-dump-key>HTTP_ACCEPT</span>" => "<span class=sf-dump-str title="3 characters">*/*</span>"
                    "<span class=sf-dump-key>HTTP_X_REQUESTED_WITH</span>" => "<span class=sf-dump-str title="14 characters">XMLHttpRequest</span>"
                    "<span class=sf-dump-key>HTTP_USER_AGENT</span>" => "<span class=sf-dump-str title="115 characters">Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36</span>"
                    "<span class=sf-dump-key>CONTENT_TYPE</span>" => "<span class=sf-dump-str title="48 characters">application/x-www-form-urlencoded; charset=UTF-8</span>"
                    "<span class=sf-dump-key>HTTP_CONTENT_TYPE</span>" => "<span class=sf-dump-str title="48 characters">application/x-www-form-urlencoded; charset=UTF-8</span>"
                    "<span class=sf-dump-key>HTTP_ORIGIN</span>" => "<span class=sf-dump-str title="21 characters">http://localhost:8000</span>"
                    "<span class=sf-dump-key>HTTP_SEC_FETCH_SITE</span>" => "<span class=sf-dump-str title="11 characters">same-origin</span>"
                    "<span class=sf-dump-key>HTTP_SEC_FETCH_MODE</span>" => "<span class=sf-dump-str title="4 characters">cors</span>"
                    "<span class=sf-dump-key>HTTP_REFERER</span>" => "<span class=sf-dump-str title="26 characters">http://localhost:8000/test</span>"
                    "<span class=sf-dump-key>HTTP_ACCEPT_ENCODING</span>" => "<span class=sf-dump-str title="17 characters">gzip, deflate, br</span>"
                    "<span class=sf-dump-key>HTTP_ACCEPT_LANGUAGE</span>" => "<span class=sf-dump-str title="23 characters">es-ES,es;q=0.9,en;q=0.8</span>"
                    "<span class=sf-dump-key>HTTP_COOKIE</span>" => "<span class=sf-dump-str title="528 characters">XSRF-TOKEN=eyJpdiI6IkNmUFBzcHZ5ZDlXSUk0YU80N1N0dGc9PSIsInZhbHVlIjoiZVwvSU4reWNEYnpmRDFFVFRENDlFcVN2ZE5RUzdcL3VIUGxzZ01tT0FscWcxZ1ljSHJ0b1N3Qm9NdDR0RXAzdlRyIiwibWFjIjoiOTM5ZWEzODhiMjFkMzVmMTlhZGUwZmIzZmE0NzAzMTFlNGY3ZmJjY2Q0Zjk4NDYwOWY3OWVjYTc4OTBiNjc1MSJ9; grower_lab_app_session=eyJpdiI6IlZYQ1V3RGlQd1g0UU11YktNWG5FaUE9PSIsInZhbHVlIjoia2J4UENqa3lVUUxpVVhaSHg0U0pLNWtTZjVwanNqTzJvbjN6Nk1tYmFwVEFCWDloaVJpZ25jQ3dHdnRWQldKUyIsIm1hYyI6IjAxNGQ3NWUzZDM4MjRmMzAzZjFiOTIxNDE5ZjM5YWY0ZDViMTA2YjMzNjYyMTYwZTZhODFlZmVjYmMwZmIyNDgifQ%3D%3D</span>"
                    "<span class=sf-dump-key>REQUEST_TIME_FLOAT</span>" => <span class=sf-dump-num>1580874591.485</span>
                    "<span class=sf-dump-key>REQUEST_TIME</span>" => <span class=sf-dump-num>1580874591</span>
                  </samp>]
                </samp>}
                +<span class=sf-dump-public title="Public property">files</span>: <span class=sf-dump-note title="Symfony\Component\HttpFoundation\FileBag
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Symfony\Component\HttpFoundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>FileBag</span> {<a class=sf-dump-ref>#56</a><samp>
                  #<span class=sf-dump-protected title="Protected property">parameters</span>: []
                </samp>}
                +<span class=sf-dump-public title="Public property">cookies</span>: <span class=sf-dump-note title="Symfony\Component\HttpFoundation\ParameterBag
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Symfony\Component\HttpFoundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ParameterBag</span> {<a class=sf-dump-ref>#54</a><samp>
                  #<span class=sf-dump-protected title="Protected property">parameters</span>: <span class=sf-dump-note>array:2</span> [<samp>
                    "<span class=sf-dump-key>XSRF-TOKEN</span>" => "<span class=sf-dump-str title="40 characters">q8idTlCCf8UGBB8y4tDc60OII3waa3DWUL7DVeyq</span>"
                    "<span class=sf-dump-key>grower_lab_app_session</span>" => "<span class=sf-dump-str title="40 characters">t6V9IsxUzYjQGS6xSehzaquvXIMPsJOriVXL5wqi</span>"
                  </samp>]
                </samp>}
                +<span class=sf-dump-public title="Public property">headers</span>: <span class=sf-dump-note title="Symfony\Component\HttpFoundation\HeaderBag
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Symfony\Component\HttpFoundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>HeaderBag</span> {<a class=sf-dump-ref>#57</a><samp>
                  #<span class=sf-dump-protected title="Protected property">headers</span>: <span class=sf-dump-note>array:14</span> [<samp>
                    "<span class=sf-dump-key>host</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="14 characters">localhost:8000</span>"
                    </samp>]
                    "<span class=sf-dump-key>connection</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="10 characters">keep-alive</span>"
                    </samp>]
                    "<span class=sf-dump-key>content-length</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">104</span>"
                    </samp>]
                    "<span class=sf-dump-key>accept</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">*/*</span>"
                    </samp>]
                    "<span class=sf-dump-key>x-requested-with</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="14 characters">XMLHttpRequest</span>"
                    </samp>]
                    "<span class=sf-dump-key>user-agent</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="115 characters">Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36</span>"
                    </samp>]
                    "<span class=sf-dump-key>content-type</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="48 characters">application/x-www-form-urlencoded; charset=UTF-8</span>"
                    </samp>]
                    "<span class=sf-dump-key>origin</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="21 characters">http://localhost:8000</span>"
                    </samp>]
                    "<span class=sf-dump-key>sec-fetch-site</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="11 characters">same-origin</span>"
                    </samp>]
                    "<span class=sf-dump-key>sec-fetch-mode</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="4 characters">cors</span>"
                    </samp>]
                    "<span class=sf-dump-key>referer</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="26 characters">http://localhost:8000/test</span>"
                    </samp>]
                    "<span class=sf-dump-key>accept-encoding</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="17 characters">gzip, deflate, br</span>"
                    </samp>]
                    "<span class=sf-dump-key>accept-language</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="23 characters">es-ES,es;q=0.9,en;q=0.8</span>"
                    </samp>]
                    "<span class=sf-dump-key>cookie</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="528 characters">XSRF-TOKEN=eyJpdiI6IkNmUFBzcHZ5ZDlXSUk0YU80N1N0dGc9PSIsInZhbHVlIjoiZVwvSU4reWNEYnpmRDFFVFRENDlFcVN2ZE5RUzdcL3VIUGxzZ01tT0FscWcxZ1ljSHJ0b1N3Qm9NdDR0RXAzdlRyIiwibWFjIjoiOTM5ZWEzODhiMjFkMzVmMTlhZGUwZmIzZmE0NzAzMTFlNGY3ZmJjY2Q0Zjk4NDYwOWY3OWVjYTc4OTBiNjc1MSJ9; grower_lab_app_session=eyJpdiI6IlZYQ1V3RGlQd1g0UU11YktNWG5FaUE9PSIsInZhbHVlIjoia2J4UENqa3lVUUxpVVhaSHg0U0pLNWtTZjVwanNqTzJvbjN6Nk1tYmFwVEFCWDloaVJpZ25jQ3dHdnRWQldKUyIsIm1hYyI6IjAxNGQ3NWUzZDM4MjRmMzAzZjFiOTIxNDE5ZjM5YWY0ZDViMTA2YjMzNjYyMTYwZTZhODFlZmVjYmMwZmIyNDgifQ%3D%3D</span>"
                    </samp>]
                  </samp>]
                  #<span class=sf-dump-protected title="Protected property">cacheControl</span>: []
                </samp>}
                #<span class=sf-dump-protected title="Protected property">content</span>: <span class=sf-dump-const>null</span>
                #<span class=sf-dump-protected title="Protected property">languages</span>: <span class=sf-dump-const>null</span>
                #<span class=sf-dump-protected title="Protected property">charsets</span>: <span class=sf-dump-const>null</span>
                #<span class=sf-dump-protected title="Protected property">encodings</span>: <span class=sf-dump-const>null</span>
                #<span class=sf-dump-protected title="Protected property">acceptableContentTypes</span>: <span class=sf-dump-const>null</span>
                #<span class=sf-dump-protected title="Protected property">pathInfo</span>: "<span class=sf-dump-str title="11 characters">/newProgram</span>"
                #<span class=sf-dump-protected title="Protected property">requestUri</span>: "<span class=sf-dump-str title="11 characters">/newProgram</span>"
                #<span class=sf-dump-protected title="Protected property">baseUrl</span>: ""
                #<span class=sf-dump-protected title="Protected property">basePath</span>: <span class=sf-dump-const>null</span>
                #<span class=sf-dump-protected title="Protected property">method</span>: "<span class=sf-dump-str title="4 characters">POST</span>"
                #<span class=sf-dump-protected title="Protected property">format</span>: <span class=sf-dump-const>null</span>
                #<span class=sf-dump-protected title="Protected property">session</span>: <span class=sf-dump-note title="Illuminate\Session\Store
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Session</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Store</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2246 title="2 occurrences">#246</a><samp id=sf-dump-1538836868-ref2246>
                  #<span class=sf-dump-protected title="Protected property">id</span>: "<span class=sf-dump-str title="40 characters">t6V9IsxUzYjQGS6xSehzaquvXIMPsJOriVXL5wqi</span>"
                  #<span class=sf-dump-protected title="Protected property">name</span>: "<span class=sf-dump-str title="22 characters">grower_lab_app_session</span>"
                  #<span class=sf-dump-protected title="Protected property">attributes</span>: <span class=sf-dump-note>array:3</span> [<samp>
                    "<span class=sf-dump-key>_token</span>" => "<span class=sf-dump-str title="40 characters">q8idTlCCf8UGBB8y4tDc60OII3waa3DWUL7DVeyq</span>"
                    "<span class=sf-dump-key>_previous</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      "<span class=sf-dump-key>url</span>" => "<span class=sf-dump-str title="26 characters">http://localhost:8000/test</span>"
                    </samp>]
                    "<span class=sf-dump-key>_flash</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                      "<span class=sf-dump-key>old</span>" => []
                      "<span class=sf-dump-key>new</span>" => []
                    </samp>]
                  </samp>]
                  #<span class=sf-dump-protected title="Protected property">handler</span>: <span class=sf-dump-note title="Illuminate\Session\FileSessionHandler
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Session</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>FileSessionHandler</span> {<a class=sf-dump-ref>#245</a><samp>
                    #<span class=sf-dump-protected title="Protected property">files</span>: <span class=sf-dump-note title="Illuminate\Filesystem\Filesystem
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Filesystem</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Filesystem</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2143 title="4 occurrences">#143</a>}
                    #<span class=sf-dump-protected title="Protected property">path</span>: "<span class=sf-dump-str title="89 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\storage\framework/sessions</span>"
                    #<span class=sf-dump-protected title="Protected property">minutes</span>: "<span class=sf-dump-str title="3 characters">120</span>"
                  </samp>}
                  #<span class=sf-dump-protected title="Protected property">started</span>: <span class=sf-dump-const>true</span>
                </samp>}
                #<span class=sf-dump-protected title="Protected property">locale</span>: <span class=sf-dump-const>null</span>
                #<span class=sf-dump-protected title="Protected property">defaultLocale</span>: "<span class=sf-dump-str title="2 characters">en</span>"
                -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\HttpFoundation\Request`">preferredFormat</span>: <span class=sf-dump-const>null</span>
                -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\HttpFoundation\Request`">isHostValid</span>: <span class=sf-dump-const>true</span>
                -<span class=sf-dump-private title="Private property defined in class:&#10;`Symfony\Component\HttpFoundation\Request`">isForwardedValid</span>: <span class=sf-dump-const>true</span>
                <span class=sf-dump-meta>basePath</span>: ""
                <span class=sf-dump-meta>format</span>: "<span class=sf-dump-str title="4 characters">html</span>"
              </samp>}
              #<span class=sf-dump-protected title="Protected property">middleware</span>: <span class=sf-dump-note>array:10</span> [<samp>
                "<span class=sf-dump-key>auth</span>" => "<span class=sf-dump-str title="32 characters">App\Http\Middleware\Authenticate</span>"
                "<span class=sf-dump-key>auth.basic</span>" => "<span class=sf-dump-str title="52 characters">Illuminate\Auth\Middleware\AuthenticateWithBasicAuth</span>"
                "<span class=sf-dump-key>bindings</span>" => "<span class=sf-dump-str title="48 characters">Illuminate\Routing\Middleware\SubstituteBindings</span>"
                "<span class=sf-dump-key>cache.headers</span>" => "<span class=sf-dump-str title="42 characters">Illuminate\Http\Middleware\SetCacheHeaders</span>"
                "<span class=sf-dump-key>can</span>" => "<span class=sf-dump-str title="36 characters">Illuminate\Auth\Middleware\Authorize</span>"
                "<span class=sf-dump-key>guest</span>" => "<span class=sf-dump-str title="43 characters">App\Http\Middleware\RedirectIfAuthenticated</span>"
                "<span class=sf-dump-key>password.confirm</span>" => "<span class=sf-dump-str title="42 characters">Illuminate\Auth\Middleware\RequirePassword</span>"
                "<span class=sf-dump-key>signed</span>" => "<span class=sf-dump-str title="47 characters">Illuminate\Routing\Middleware\ValidateSignature</span>"
                "<span class=sf-dump-key>throttle</span>" => "<span class=sf-dump-str title="46 characters">Illuminate\Routing\Middleware\ThrottleRequests</span>"
                "<span class=sf-dump-key>verified</span>" => "<span class=sf-dump-str title="48 characters">Illuminate\Auth\Middleware\EnsureEmailIsVerified</span>"
              </samp>]
              #<span class=sf-dump-protected title="Protected property">middlewareGroups</span>: <span class=sf-dump-note>array:2</span> [<samp>
                "<span class=sf-dump-key>web</span>" => <span class=sf-dump-note>array:6</span> [<samp>
                  <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="34 characters">App\Http\Middleware\EncryptCookies</span>"
                  <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="55 characters">Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse</span>"
                  <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="42 characters">Illuminate\Session\Middleware\StartSession</span>"
                  <span class=sf-dump-index>3</span> => "<span class=sf-dump-str title="49 characters">Illuminate\View\Middleware\ShareErrorsFromSession</span>"
                  <span class=sf-dump-index>4</span> => "<span class=sf-dump-str title="35 characters">App\Http\Middleware\VerifyCsrfToken</span>"
                  <span class=sf-dump-index>5</span> => "<span class=sf-dump-str title="48 characters">Illuminate\Routing\Middleware\SubstituteBindings</span>"
                </samp>]
                "<span class=sf-dump-key>api</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                  <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="13 characters">throttle:60,1</span>"
                  <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="48 characters">Illuminate\Routing\Middleware\SubstituteBindings</span>"
                </samp>]
              </samp>]
              +<span class=sf-dump-public title="Public property">middlewarePriority</span>: <span class=sf-dump-note>array:7</span> [<samp>
                <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="42 characters">Illuminate\Session\Middleware\StartSession</span>"
                <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="49 characters">Illuminate\View\Middleware\ShareErrorsFromSession</span>"
                <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="32 characters">App\Http\Middleware\Authenticate</span>"
                <span class=sf-dump-index>3</span> => "<span class=sf-dump-str title="46 characters">Illuminate\Routing\Middleware\ThrottleRequests</span>"
                <span class=sf-dump-index>4</span> => "<span class=sf-dump-str title="49 characters">Illuminate\Session\Middleware\AuthenticateSession</span>"
                <span class=sf-dump-index>5</span> => "<span class=sf-dump-str title="48 characters">Illuminate\Routing\Middleware\SubstituteBindings</span>"
                <span class=sf-dump-index>6</span> => "<span class=sf-dump-str title="36 characters">Illuminate\Auth\Middleware\Authorize</span>"
              </samp>]
              #<span class=sf-dump-protected title="Protected property">binders</span>: []
              #<span class=sf-dump-protected title="Protected property">patterns</span>: []
              #<span class=sf-dump-protected title="Protected property">groupStack</span>: []
            </samp>}
            "<span class=sf-dump-key>Illuminate\Contracts\Http\Kernel</span>" => <span class=sf-dump-note title="App\Http\Kernel
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">App\Http</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Kernel</span> {<a class=sf-dump-ref>#38</a><samp>
              #<span class=sf-dump-protected title="Protected property">middleware</span>: <span class=sf-dump-note>array:5</span> [<samp>
                <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="32 characters">App\Http\Middleware\TrustProxies</span>"
                <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="43 characters">App\Http\Middleware\CheckForMaintenanceMode</span>"
                <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="54 characters">Illuminate\Foundation\Http\Middleware\ValidatePostSize</span>"
                <span class=sf-dump-index>3</span> => "<span class=sf-dump-str title="31 characters">App\Http\Middleware\TrimStrings</span>"
                <span class=sf-dump-index>4</span> => "<span class=sf-dump-str title="63 characters">Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull</span>"
              </samp>]
              #<span class=sf-dump-protected title="Protected property">middlewareGroups</span>: <span class=sf-dump-note>array:2</span> [<samp>
                "<span class=sf-dump-key>web</span>" => <span class=sf-dump-note>array:6</span> [<samp>
                  <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="34 characters">App\Http\Middleware\EncryptCookies</span>"
                  <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="55 characters">Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse</span>"
                  <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="42 characters">Illuminate\Session\Middleware\StartSession</span>"
                  <span class=sf-dump-index>3</span> => "<span class=sf-dump-str title="49 characters">Illuminate\View\Middleware\ShareErrorsFromSession</span>"
                  <span class=sf-dump-index>4</span> => "<span class=sf-dump-str title="35 characters">App\Http\Middleware\VerifyCsrfToken</span>"
                  <span class=sf-dump-index>5</span> => "<span class=sf-dump-str title="48 characters">Illuminate\Routing\Middleware\SubstituteBindings</span>"
                </samp>]
                "<span class=sf-dump-key>api</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                  <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="13 characters">throttle:60,1</span>"
                  <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="48 characters">Illuminate\Routing\Middleware\SubstituteBindings</span>"
                </samp>]
              </samp>]
              #<span class=sf-dump-protected title="Protected property">routeMiddleware</span>: <span class=sf-dump-note>array:10</span> [<samp>
                "<span class=sf-dump-key>auth</span>" => "<span class=sf-dump-str title="32 characters">App\Http\Middleware\Authenticate</span>"
                "<span class=sf-dump-key>auth.basic</span>" => "<span class=sf-dump-str title="52 characters">Illuminate\Auth\Middleware\AuthenticateWithBasicAuth</span>"
                "<span class=sf-dump-key>bindings</span>" => "<span class=sf-dump-str title="48 characters">Illuminate\Routing\Middleware\SubstituteBindings</span>"
                "<span class=sf-dump-key>cache.headers</span>" => "<span class=sf-dump-str title="42 characters">Illuminate\Http\Middleware\SetCacheHeaders</span>"
                "<span class=sf-dump-key>can</span>" => "<span class=sf-dump-str title="36 characters">Illuminate\Auth\Middleware\Authorize</span>"
                "<span class=sf-dump-key>guest</span>" => "<span class=sf-dump-str title="43 characters">App\Http\Middleware\RedirectIfAuthenticated</span>"
                "<span class=sf-dump-key>password.confirm</span>" => "<span class=sf-dump-str title="42 characters">Illuminate\Auth\Middleware\RequirePassword</span>"
                "<span class=sf-dump-key>signed</span>" => "<span class=sf-dump-str title="47 characters">Illuminate\Routing\Middleware\ValidateSignature</span>"
                "<span class=sf-dump-key>throttle</span>" => "<span class=sf-dump-str title="46 characters">Illuminate\Routing\Middleware\ThrottleRequests</span>"
                "<span class=sf-dump-key>verified</span>" => "<span class=sf-dump-str title="48 characters">Illuminate\Auth\Middleware\EnsureEmailIsVerified</span>"
              </samp>]
              #<span class=sf-dump-protected title="Protected property">middlewarePriority</span>: <span class=sf-dump-note>array:7</span> [<samp>
                <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="42 characters">Illuminate\Session\Middleware\StartSession</span>"
                <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="49 characters">Illuminate\View\Middleware\ShareErrorsFromSession</span>"
                <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="32 characters">App\Http\Middleware\Authenticate</span>"
                <span class=sf-dump-index>3</span> => "<span class=sf-dump-str title="46 characters">Illuminate\Routing\Middleware\ThrottleRequests</span>"
                <span class=sf-dump-index>4</span> => "<span class=sf-dump-str title="49 characters">Illuminate\Session\Middleware\AuthenticateSession</span>"
                <span class=sf-dump-index>5</span> => "<span class=sf-dump-str title="48 characters">Illuminate\Routing\Middleware\SubstituteBindings</span>"
                <span class=sf-dump-index>6</span> => "<span class=sf-dump-str title="36 characters">Illuminate\Auth\Middleware\Authorize</span>"
              </samp>]
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
              #<span class=sf-dump-protected title="Protected property">router</span>: <span class=sf-dump-note title="Illuminate\Routing\Router
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Router</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref234 title="19 occurrences">#34</a>}
              #<span class=sf-dump-protected title="Protected property">bootstrappers</span>: <span class=sf-dump-note>array:6</span> [<samp>
                <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="56 characters">Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables</span>"
                <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="49 characters">Illuminate\Foundation\Bootstrap\LoadConfiguration</span>"
                <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="48 characters">Illuminate\Foundation\Bootstrap\HandleExceptions</span>"
                <span class=sf-dump-index>3</span> => "<span class=sf-dump-str title="47 characters">Illuminate\Foundation\Bootstrap\RegisterFacades</span>"
                <span class=sf-dump-index>4</span> => "<span class=sf-dump-str title="49 characters">Illuminate\Foundation\Bootstrap\RegisterProviders</span>"
                <span class=sf-dump-index>5</span> => "<span class=sf-dump-str title="45 characters">Illuminate\Foundation\Bootstrap\BootProviders</span>"
              </samp>]
            </samp>}
            "<span class=sf-dump-key>request</span>" => <span class=sf-dump-note title="Illuminate\Http\Request
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Http</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Request</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref251 title="3 occurrences">#51</a>}
            "<span class=sf-dump-key>config</span>" => <span class=sf-dump-note title="Illuminate\Config\Repository
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Config</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Repository</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref242 title="2 occurrences">#42</a><samp id=sf-dump-1538836868-ref242>
              #<span class=sf-dump-protected title="Protected property">items</span>: <span class=sf-dump-note>array:16</span> [<samp>
                "<span class=sf-dump-key>app</span>" => <span class=sf-dump-note>array:13</span> [<samp>
                  "<span class=sf-dump-key>name</span>" => "<span class=sf-dump-str title="14 characters">Grower-lab-app</span>"
                  "<span class=sf-dump-key>env</span>" => "<span class=sf-dump-str title="5 characters">local</span>"
                  "<span class=sf-dump-key>debug</span>" => <span class=sf-dump-const>true</span>
                  "<span class=sf-dump-key>url</span>" => "<span class=sf-dump-str title="16 characters">http://localhost</span>"
                  "<span class=sf-dump-key>asset_url</span>" => <span class=sf-dump-const>null</span>
                  "<span class=sf-dump-key>timezone</span>" => "<span class=sf-dump-str title="3 characters">UTC</span>"
                  "<span class=sf-dump-key>locale</span>" => "<span class=sf-dump-str title="2 characters">en</span>"
                  "<span class=sf-dump-key>fallback_locale</span>" => "<span class=sf-dump-str title="2 characters">en</span>"
                  "<span class=sf-dump-key>faker_locale</span>" => "<span class=sf-dump-str title="5 characters">en_US</span>"
                  "<span class=sf-dump-key>key</span>" => "<span class=sf-dump-str title="51 characters">base64:ddP8/7MzaR+EksFLIpw+gzyJjfi6fWvbjhvRoDdaRBc=</span>"
                  "<span class=sf-dump-key>cipher</span>" => "<span class=sf-dump-str title="11 characters">AES-256-CBC</span>"
                  "<span class=sf-dump-key>providers</span>" => <span class=sf-dump-note>array:26</span> [<samp>
                    <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="35 characters">Illuminate\Auth\AuthServiceProvider</span>"
                    <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="48 characters">Illuminate\Broadcasting\BroadcastServiceProvider</span>"
                    <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="33 characters">Illuminate\Bus\BusServiceProvider</span>"
                    <span class=sf-dump-index>3</span> => "<span class=sf-dump-str title="37 characters">Illuminate\Cache\CacheServiceProvider</span>"
                    <span class=sf-dump-index>4</span> => "<span class=sf-dump-str title="61 characters">Illuminate\Foundation\Providers\ConsoleSupportServiceProvider</span>"
                    <span class=sf-dump-index>5</span> => "<span class=sf-dump-str title="39 characters">Illuminate\Cookie\CookieServiceProvider</span>"
                    <span class=sf-dump-index>6</span> => "<span class=sf-dump-str title="43 characters">Illuminate\Database\DatabaseServiceProvider</span>"
                    <span class=sf-dump-index>7</span> => "<span class=sf-dump-str title="47 characters">Illuminate\Encryption\EncryptionServiceProvider</span>"
                    <span class=sf-dump-index>8</span> => "<span class=sf-dump-str title="47 characters">Illuminate\Filesystem\FilesystemServiceProvider</span>"
                    <span class=sf-dump-index>9</span> => "<span class=sf-dump-str title="57 characters">Illuminate\Foundation\Providers\FoundationServiceProvider</span>"
                    <span class=sf-dump-index>10</span> => "<span class=sf-dump-str title="38 characters">Illuminate\Hashing\HashServiceProvider</span>"
                    <span class=sf-dump-index>11</span> => "<span class=sf-dump-str title="35 characters">Illuminate\Mail\MailServiceProvider</span>"
                    <span class=sf-dump-index>12</span> => "<span class=sf-dump-str title="52 characters">Illuminate\Notifications\NotificationServiceProvider</span>"
                    <span class=sf-dump-index>13</span> => "<span class=sf-dump-str title="47 characters">Illuminate\Pagination\PaginationServiceProvider</span>"
                    <span class=sf-dump-index>14</span> => "<span class=sf-dump-str title="43 characters">Illuminate\Pipeline\PipelineServiceProvider</span>"
                    <span class=sf-dump-index>15</span> => "<span class=sf-dump-str title="37 characters">Illuminate\Queue\QueueServiceProvider</span>"
                    <span class=sf-dump-index>16</span> => "<span class=sf-dump-str title="37 characters">Illuminate\Redis\RedisServiceProvider</span>"
                    <span class=sf-dump-index>17</span> => "<span class=sf-dump-str title="54 characters">Illuminate\Auth\Passwords\PasswordResetServiceProvider</span>"
                    <span class=sf-dump-index>18</span> => "<span class=sf-dump-str title="41 characters">Illuminate\Session\SessionServiceProvider</span>"
                    <span class=sf-dump-index>19</span> => "<span class=sf-dump-str title="49 characters">Illuminate\Translation\TranslationServiceProvider</span>"
                    <span class=sf-dump-index>20</span> => "<span class=sf-dump-str title="47 characters">Illuminate\Validation\ValidationServiceProvider</span>"
                    <span class=sf-dump-index>21</span> => "<span class=sf-dump-str title="35 characters">Illuminate\View\ViewServiceProvider</span>"
                    <span class=sf-dump-index>22</span> => "<span class=sf-dump-str title="32 characters">App\Providers\AppServiceProvider</span>"
                    <span class=sf-dump-index>23</span> => "<span class=sf-dump-str title="33 characters">App\Providers\AuthServiceProvider</span>"
                    <span class=sf-dump-index>24</span> => "<span class=sf-dump-str title="34 characters">App\Providers\EventServiceProvider</span>"
                    <span class=sf-dump-index>25</span> => "<span class=sf-dump-str title="34 characters">App\Providers\RouteServiceProvider</span>"
                  </samp>]
                  "<span class=sf-dump-key>aliases</span>" => <span class=sf-dump-note>array:35</span> [<samp>
                    "<span class=sf-dump-key>App</span>" => "<span class=sf-dump-str title="30 characters">Illuminate\Support\Facades\App</span>"
                    "<span class=sf-dump-key>Arr</span>" => "<span class=sf-dump-str title="22 characters">Illuminate\Support\Arr</span>"
                    "<span class=sf-dump-key>Artisan</span>" => "<span class=sf-dump-str title="34 characters">Illuminate\Support\Facades\Artisan</span>"
                    "<span class=sf-dump-key>Auth</span>" => "<span class=sf-dump-str title="31 characters">Illuminate\Support\Facades\Auth</span>"
                    "<span class=sf-dump-key>Blade</span>" => "<span class=sf-dump-str title="32 characters">Illuminate\Support\Facades\Blade</span>"
                    "<span class=sf-dump-key>Broadcast</span>" => "<span class=sf-dump-str title="36 characters">Illuminate\Support\Facades\Broadcast</span>"
                    "<span class=sf-dump-key>Bus</span>" => "<span class=sf-dump-str title="30 characters">Illuminate\Support\Facades\Bus</span>"
                    "<span class=sf-dump-key>Cache</span>" => "<span class=sf-dump-str title="32 characters">Illuminate\Support\Facades\Cache</span>"
                    "<span class=sf-dump-key>Config</span>" => "<span class=sf-dump-str title="33 characters">Illuminate\Support\Facades\Config</span>"
                    "<span class=sf-dump-key>Cookie</span>" => "<span class=sf-dump-str title="33 characters">Illuminate\Support\Facades\Cookie</span>"
                    "<span class=sf-dump-key>Crypt</span>" => "<span class=sf-dump-str title="32 characters">Illuminate\Support\Facades\Crypt</span>"
                    "<span class=sf-dump-key>DB</span>" => "<span class=sf-dump-str title="29 characters">Illuminate\Support\Facades\DB</span>"
                    "<span class=sf-dump-key>Eloquent</span>" => "<span class=sf-dump-str title="34 characters">Illuminate\Database\Eloquent\Model</span>"
                    "<span class=sf-dump-key>Event</span>" => "<span class=sf-dump-str title="32 characters">Illuminate\Support\Facades\Event</span>"
                    "<span class=sf-dump-key>File</span>" => "<span class=sf-dump-str title="31 characters">Illuminate\Support\Facades\File</span>"
                    "<span class=sf-dump-key>Gate</span>" => "<span class=sf-dump-str title="31 characters">Illuminate\Support\Facades\Gate</span>"
                    "<span class=sf-dump-key>Hash</span>" => "<span class=sf-dump-str title="31 characters">Illuminate\Support\Facades\Hash</span>"
                    "<span class=sf-dump-key>Lang</span>" => "<span class=sf-dump-str title="31 characters">Illuminate\Support\Facades\Lang</span>"
                    "<span class=sf-dump-key>Log</span>" => "<span class=sf-dump-str title="30 characters">Illuminate\Support\Facades\Log</span>"
                    "<span class=sf-dump-key>Mail</span>" => "<span class=sf-dump-str title="31 characters">Illuminate\Support\Facades\Mail</span>"
                    "<span class=sf-dump-key>Notification</span>" => "<span class=sf-dump-str title="39 characters">Illuminate\Support\Facades\Notification</span>"
                    "<span class=sf-dump-key>Password</span>" => "<span class=sf-dump-str title="35 characters">Illuminate\Support\Facades\Password</span>"
                    "<span class=sf-dump-key>Queue</span>" => "<span class=sf-dump-str title="32 characters">Illuminate\Support\Facades\Queue</span>"
                    "<span class=sf-dump-key>Redirect</span>" => "<span class=sf-dump-str title="35 characters">Illuminate\Support\Facades\Redirect</span>"
                    "<span class=sf-dump-key>Redis</span>" => "<span class=sf-dump-str title="32 characters">Illuminate\Support\Facades\Redis</span>"
                    "<span class=sf-dump-key>Request</span>" => "<span class=sf-dump-str title="34 characters">Illuminate\Support\Facades\Request</span>"
                    "<span class=sf-dump-key>Response</span>" => "<span class=sf-dump-str title="35 characters">Illuminate\Support\Facades\Response</span>"
                    "<span class=sf-dump-key>Route</span>" => "<span class=sf-dump-str title="32 characters">Illuminate\Support\Facades\Route</span>"
                    "<span class=sf-dump-key>Schema</span>" => "<span class=sf-dump-str title="33 characters">Illuminate\Support\Facades\Schema</span>"
                    "<span class=sf-dump-key>Session</span>" => "<span class=sf-dump-str title="34 characters">Illuminate\Support\Facades\Session</span>"
                    "<span class=sf-dump-key>Storage</span>" => "<span class=sf-dump-str title="34 characters">Illuminate\Support\Facades\Storage</span>"
                    "<span class=sf-dump-key>Str</span>" => "<span class=sf-dump-str title="22 characters">Illuminate\Support\Str</span>"
                    "<span class=sf-dump-key>URL</span>" => "<span class=sf-dump-str title="30 characters">Illuminate\Support\Facades\URL</span>"
                    "<span class=sf-dump-key>Validator</span>" => "<span class=sf-dump-str title="36 characters">Illuminate\Support\Facades\Validator</span>"
                    "<span class=sf-dump-key>View</span>" => "<span class=sf-dump-str title="31 characters">Illuminate\Support\Facades\View</span>"
                  </samp>]
                </samp>]
                "<span class=sf-dump-key>auth</span>" => <span class=sf-dump-note>array:5</span> [<samp>
                  "<span class=sf-dump-key>defaults</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                    "<span class=sf-dump-key>guard</span>" => "<span class=sf-dump-str title="3 characters">web</span>"
                    "<span class=sf-dump-key>passwords</span>" => "<span class=sf-dump-str title="5 characters">users</span>"
                  </samp>]
                  "<span class=sf-dump-key>guards</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                    "<span class=sf-dump-key>web</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="7 characters">session</span>"
                      "<span class=sf-dump-key>provider</span>" => "<span class=sf-dump-str title="5 characters">users</span>"
                    </samp>]
                    "<span class=sf-dump-key>api</span>" => <span class=sf-dump-note>array:3</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="5 characters">token</span>"
                      "<span class=sf-dump-key>provider</span>" => "<span class=sf-dump-str title="5 characters">users</span>"
                      "<span class=sf-dump-key>hash</span>" => <span class=sf-dump-const>false</span>
                    </samp>]
                  </samp>]
                  "<span class=sf-dump-key>providers</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                    "<span class=sf-dump-key>users</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="8 characters">eloquent</span>"
                      "<span class=sf-dump-key>model</span>" => "<span class=sf-dump-str title="8 characters">App\User</span>"
                    </samp>]
                  </samp>]
                  "<span class=sf-dump-key>passwords</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                    "<span class=sf-dump-key>users</span>" => <span class=sf-dump-note>array:4</span> [<samp>
                      "<span class=sf-dump-key>provider</span>" => "<span class=sf-dump-str title="5 characters">users</span>"
                      "<span class=sf-dump-key>table</span>" => "<span class=sf-dump-str title="15 characters">password_resets</span>"
                      "<span class=sf-dump-key>expire</span>" => <span class=sf-dump-num>60</span>
                      "<span class=sf-dump-key>throttle</span>" => <span class=sf-dump-num>60</span>
                    </samp>]
                  </samp>]
                  "<span class=sf-dump-key>password_timeout</span>" => <span class=sf-dump-num>10800</span>
                </samp>]
                "<span class=sf-dump-key>broadcasting</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                  "<span class=sf-dump-key>default</span>" => "<span class=sf-dump-str title="3 characters">log</span>"
                  "<span class=sf-dump-key>connections</span>" => <span class=sf-dump-note>array:4</span> [<samp>
                    "<span class=sf-dump-key>pusher</span>" => <span class=sf-dump-note>array:5</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="6 characters">pusher</span>"
                      "<span class=sf-dump-key>key</span>" => ""
                      "<span class=sf-dump-key>secret</span>" => ""
                      "<span class=sf-dump-key>app_id</span>" => ""
                      "<span class=sf-dump-key>options</span>" => <span class=sf-dump-note>array:2</span> [ &hellip;2]
                    </samp>]
                    "<span class=sf-dump-key>redis</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="5 characters">redis</span>"
                      "<span class=sf-dump-key>connection</span>" => "<span class=sf-dump-str title="7 characters">default</span>"
                    </samp>]
                    "<span class=sf-dump-key>log</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="3 characters">log</span>"
                    </samp>]
                    "<span class=sf-dump-key>null</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="4 characters">null</span>"
                    </samp>]
                  </samp>]
                </samp>]
                "<span class=sf-dump-key>cache</span>" => <span class=sf-dump-note>array:3</span> [<samp>
                  "<span class=sf-dump-key>default</span>" => "<span class=sf-dump-str title="4 characters">file</span>"
                  "<span class=sf-dump-key>stores</span>" => <span class=sf-dump-note>array:7</span> [<samp>
                    "<span class=sf-dump-key>apc</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="3 characters">apc</span>"
                    </samp>]
                    "<span class=sf-dump-key>array</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="5 characters">array</span>"
                    </samp>]
                    "<span class=sf-dump-key>database</span>" => <span class=sf-dump-note>array:3</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="8 characters">database</span>"
                      "<span class=sf-dump-key>table</span>" => "<span class=sf-dump-str title="5 characters">cache</span>"
                      "<span class=sf-dump-key>connection</span>" => <span class=sf-dump-const>null</span>
                    </samp>]
                    "<span class=sf-dump-key>file</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="4 characters">file</span>"
                      "<span class=sf-dump-key>path</span>" => "<span class=sf-dump-str title="91 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\storage\framework/cache/data</span>"
                    </samp>]
                    "<span class=sf-dump-key>memcached</span>" => <span class=sf-dump-note>array:5</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="9 characters">memcached</span>"
                      "<span class=sf-dump-key>persistent_id</span>" => <span class=sf-dump-const>null</span>
                      "<span class=sf-dump-key>sasl</span>" => <span class=sf-dump-note>array:2</span> [ &hellip;2]
                      "<span class=sf-dump-key>options</span>" => []
                      "<span class=sf-dump-key>servers</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                    </samp>]
                    "<span class=sf-dump-key>redis</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="5 characters">redis</span>"
                      "<span class=sf-dump-key>connection</span>" => "<span class=sf-dump-str title="5 characters">cache</span>"
                    </samp>]
                    "<span class=sf-dump-key>dynamodb</span>" => <span class=sf-dump-note>array:6</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="8 characters">dynamodb</span>"
                      "<span class=sf-dump-key>key</span>" => ""
                      "<span class=sf-dump-key>secret</span>" => ""
                      "<span class=sf-dump-key>region</span>" => "<span class=sf-dump-str title="9 characters">us-east-1</span>"
                      "<span class=sf-dump-key>table</span>" => "<span class=sf-dump-str title="5 characters">cache</span>"
                      "<span class=sf-dump-key>endpoint</span>" => <span class=sf-dump-const>null</span>
                    </samp>]
                  </samp>]
                  "<span class=sf-dump-key>prefix</span>" => "<span class=sf-dump-str title="20 characters">grower_lab_app_cache</span>"
                </samp>]
                "<span class=sf-dump-key>database</span>" => <span class=sf-dump-note>array:4</span> [<samp>
                  "<span class=sf-dump-key>default</span>" => "<span class=sf-dump-str title="5 characters">mysql</span>"
                  "<span class=sf-dump-key>connections</span>" => <span class=sf-dump-note>array:4</span> [<samp>
                    "<span class=sf-dump-key>sqlite</span>" => <span class=sf-dump-note>array:5</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="6 characters">sqlite</span>"
                      "<span class=sf-dump-key>url</span>" => <span class=sf-dump-const>null</span>
                      "<span class=sf-dump-key>database</span>" => "<span class=sf-dump-str title="10 characters">grower-lab</span>"
                      "<span class=sf-dump-key>prefix</span>" => ""
                      "<span class=sf-dump-key>foreign_key_constraints</span>" => <span class=sf-dump-const>true</span>
                    </samp>]
                    "<span class=sf-dump-key>mysql</span>" => <span class=sf-dump-note>array:15</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="5 characters">mysql</span>"
                      "<span class=sf-dump-key>url</span>" => <span class=sf-dump-const>null</span>
                      "<span class=sf-dump-key>host</span>" => "<span class=sf-dump-str title="9 characters">127.0.0.1</span>"
                      "<span class=sf-dump-key>port</span>" => "<span class=sf-dump-str title="4 characters">3306</span>"
                      "<span class=sf-dump-key>database</span>" => "<span class=sf-dump-str title="10 characters">grower-lab</span>"
                      "<span class=sf-dump-key>username</span>" => "<span class=sf-dump-str title="4 characters">root</span>"
                      "<span class=sf-dump-key>password</span>" => ""
                      "<span class=sf-dump-key>unix_socket</span>" => ""
                      "<span class=sf-dump-key>charset</span>" => "<span class=sf-dump-str title="7 characters">utf8mb4</span>"
                      "<span class=sf-dump-key>collation</span>" => "<span class=sf-dump-str title="18 characters">utf8mb4_unicode_ci</span>"
                      "<span class=sf-dump-key>prefix</span>" => ""
                      "<span class=sf-dump-key>prefix_indexes</span>" => <span class=sf-dump-const>true</span>
                      "<span class=sf-dump-key>strict</span>" => <span class=sf-dump-const>true</span>
                      "<span class=sf-dump-key>engine</span>" => <span class=sf-dump-const>null</span>
                      "<span class=sf-dump-key>options</span>" => []
                    </samp>]
                    "<span class=sf-dump-key>pgsql</span>" => <span class=sf-dump-note>array:12</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="5 characters">pgsql</span>"
                      "<span class=sf-dump-key>url</span>" => <span class=sf-dump-const>null</span>
                      "<span class=sf-dump-key>host</span>" => "<span class=sf-dump-str title="9 characters">127.0.0.1</span>"
                      "<span class=sf-dump-key>port</span>" => "<span class=sf-dump-str title="4 characters">3306</span>"
                      "<span class=sf-dump-key>database</span>" => "<span class=sf-dump-str title="10 characters">grower-lab</span>"
                      "<span class=sf-dump-key>username</span>" => "<span class=sf-dump-str title="4 characters">root</span>"
                      "<span class=sf-dump-key>password</span>" => ""
                      "<span class=sf-dump-key>charset</span>" => "<span class=sf-dump-str title="4 characters">utf8</span>"
                      "<span class=sf-dump-key>prefix</span>" => ""
                      "<span class=sf-dump-key>prefix_indexes</span>" => <span class=sf-dump-const>true</span>
                      "<span class=sf-dump-key>schema</span>" => "<span class=sf-dump-str title="6 characters">public</span>"
                      "<span class=sf-dump-key>sslmode</span>" => "<span class=sf-dump-str title="6 characters">prefer</span>"
                    </samp>]
                    "<span class=sf-dump-key>sqlsrv</span>" => <span class=sf-dump-note>array:10</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="6 characters">sqlsrv</span>"
                      "<span class=sf-dump-key>url</span>" => <span class=sf-dump-const>null</span>
                      "<span class=sf-dump-key>host</span>" => "<span class=sf-dump-str title="9 characters">127.0.0.1</span>"
                      "<span class=sf-dump-key>port</span>" => "<span class=sf-dump-str title="4 characters">3306</span>"
                      "<span class=sf-dump-key>database</span>" => "<span class=sf-dump-str title="10 characters">grower-lab</span>"
                      "<span class=sf-dump-key>username</span>" => "<span class=sf-dump-str title="4 characters">root</span>"
                      "<span class=sf-dump-key>password</span>" => ""
                      "<span class=sf-dump-key>charset</span>" => "<span class=sf-dump-str title="4 characters">utf8</span>"
                      "<span class=sf-dump-key>prefix</span>" => ""
                      "<span class=sf-dump-key>prefix_indexes</span>" => <span class=sf-dump-const>true</span>
                    </samp>]
                  </samp>]
                  "<span class=sf-dump-key>migrations</span>" => "<span class=sf-dump-str title="10 characters">migrations</span>"
                  "<span class=sf-dump-key>redis</span>" => <span class=sf-dump-note>array:4</span> [<samp>
                    "<span class=sf-dump-key>client</span>" => "<span class=sf-dump-str title="8 characters">phpredis</span>"
                    "<span class=sf-dump-key>options</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                      "<span class=sf-dump-key>cluster</span>" => "<span class=sf-dump-str title="5 characters">redis</span>"
                      "<span class=sf-dump-key>prefix</span>" => "<span class=sf-dump-str title="24 characters">grower_lab_app_database_</span>"
                    </samp>]
                    "<span class=sf-dump-key>default</span>" => <span class=sf-dump-note>array:5</span> [<samp>
                      "<span class=sf-dump-key>url</span>" => <span class=sf-dump-const>null</span>
                      "<span class=sf-dump-key>host</span>" => "<span class=sf-dump-str title="9 characters">127.0.0.1</span>"
                      "<span class=sf-dump-key>password</span>" => <span class=sf-dump-const>null</span>
                      "<span class=sf-dump-key>port</span>" => "<span class=sf-dump-str title="4 characters">6379</span>"
                      "<span class=sf-dump-key>database</span>" => "<span class=sf-dump-str>0</span>"
                    </samp>]
                    "<span class=sf-dump-key>cache</span>" => <span class=sf-dump-note>array:5</span> [<samp>
                      "<span class=sf-dump-key>url</span>" => <span class=sf-dump-const>null</span>
                      "<span class=sf-dump-key>host</span>" => "<span class=sf-dump-str title="9 characters">127.0.0.1</span>"
                      "<span class=sf-dump-key>password</span>" => <span class=sf-dump-const>null</span>
                      "<span class=sf-dump-key>port</span>" => "<span class=sf-dump-str title="4 characters">6379</span>"
                      "<span class=sf-dump-key>database</span>" => "<span class=sf-dump-str>1</span>"
                    </samp>]
                  </samp>]
                </samp>]
                "<span class=sf-dump-key>filesystems</span>" => <span class=sf-dump-note>array:3</span> [<samp>
                  "<span class=sf-dump-key>default</span>" => "<span class=sf-dump-str title="5 characters">local</span>"
                  "<span class=sf-dump-key>cloud</span>" => "<span class=sf-dump-str title="2 characters">s3</span>"
                  "<span class=sf-dump-key>disks</span>" => <span class=sf-dump-note>array:3</span> [<samp>
                    "<span class=sf-dump-key>local</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="5 characters">local</span>"
                      "<span class=sf-dump-key>root</span>" => "<span class=sf-dump-str title="74 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\storage\app</span>"
                    </samp>]
                    "<span class=sf-dump-key>public</span>" => <span class=sf-dump-note>array:4</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="5 characters">local</span>"
                      "<span class=sf-dump-key>root</span>" => "<span class=sf-dump-str title="81 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\storage\app/public</span>"
                      "<span class=sf-dump-key>url</span>" => "<span class=sf-dump-str title="24 characters">http://localhost/storage</span>"
                      "<span class=sf-dump-key>visibility</span>" => "<span class=sf-dump-str title="6 characters">public</span>"
                    </samp>]
                    "<span class=sf-dump-key>s3</span>" => <span class=sf-dump-note>array:6</span> [<samp>
                      "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="2 characters">s3</span>"
                      "<span class=sf-dump-key>key</span>" => ""
                      "<span class=sf-dump-key>secret</span>" => ""
                      "<span class=sf-dump-key>region</span>" => "<span class=sf-dump-str title="9 characters">us-east-1</span>"
                       &hellip;2
                    </samp>]
                  </samp>]
                </samp>]
                "<span class=sf-dump-key>hashing</span>" => <span class=sf-dump-note>array:3</span> [<samp>
                  "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="6 characters">bcrypt</span>"
                  "<span class=sf-dump-key>bcrypt</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                    "<span class=sf-dump-key>rounds</span>" => <span class=sf-dump-num>10</span>
                  </samp>]
                  "<span class=sf-dump-key>argon</span>" => <span class=sf-dump-note>array:3</span> [<samp>
                    "<span class=sf-dump-key>memory</span>" => <span class=sf-dump-num>1024</span>
                    "<span class=sf-dump-key>threads</span>" => <span class=sf-dump-num>2</span>
                    "<span class=sf-dump-key>time</span>" => <span class=sf-dump-num>2</span>
                  </samp>]
                </samp>]
                "<span class=sf-dump-key>logging</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                  "<span class=sf-dump-key>default</span>" => "<span class=sf-dump-str title="5 characters">stack</span>"
                  "<span class=sf-dump-key>channels</span>" => <span class=sf-dump-note>array:10</span> [<samp>
                    "<span class=sf-dump-key>stack</span>" => <span class=sf-dump-note>array:3</span> [ &hellip;3]
                    "<span class=sf-dump-key>single</span>" => <span class=sf-dump-note>array:3</span> [ &hellip;3]
                    "<span class=sf-dump-key>daily</span>" => <span class=sf-dump-note>array:4</span> [ &hellip;4]
                    "<span class=sf-dump-key>slack</span>" => <span class=sf-dump-note>array:5</span> [ &hellip;5]
                    "<span class=sf-dump-key>papertrail</span>" => <span class=sf-dump-note>array:4</span> [ &hellip;4]
                    "<span class=sf-dump-key>stderr</span>" => <span class=sf-dump-note>array:4</span> [ &hellip;4]
                    "<span class=sf-dump-key>syslog</span>" => <span class=sf-dump-note>array:2</span> [ &hellip;2]
                    "<span class=sf-dump-key>errorlog</span>" => <span class=sf-dump-note>array:2</span> [ &hellip;2]
                    "<span class=sf-dump-key>null</span>" => <span class=sf-dump-note>array:2</span> [ &hellip;2]
                    "<span class=sf-dump-key>emergency</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                  </samp>]
                </samp>]
                "<span class=sf-dump-key>mail</span>" => <span class=sf-dump-note>array:10</span> [<samp>
                  "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="4 characters">smtp</span>"
                  "<span class=sf-dump-key>host</span>" => "<span class=sf-dump-str title="16 characters">smtp.mailtrap.io</span>"
                  "<span class=sf-dump-key>port</span>" => "<span class=sf-dump-str title="4 characters">2525</span>"
                  "<span class=sf-dump-key>from</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                    "<span class=sf-dump-key>address</span>" => <span class=sf-dump-const>null</span>
                    "<span class=sf-dump-key>name</span>" => "<span class=sf-dump-str title="14 characters">Grower-lab-app</span>"
                  </samp>]
                  "<span class=sf-dump-key>encryption</span>" => <span class=sf-dump-const>null</span>
                  "<span class=sf-dump-key>username</span>" => <span class=sf-dump-const>null</span>
                  "<span class=sf-dump-key>password</span>" => <span class=sf-dump-const>null</span>
                  "<span class=sf-dump-key>sendmail</span>" => "<span class=sf-dump-str title="22 characters">/usr/sbin/sendmail -bs</span>"
                  "<span class=sf-dump-key>markdown</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                    "<span class=sf-dump-key>theme</span>" => "<span class=sf-dump-str title="7 characters">default</span>"
                    "<span class=sf-dump-key>paths</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                  </samp>]
                  "<span class=sf-dump-key>log_channel</span>" => <span class=sf-dump-const>null</span>
                </samp>]
                "<span class=sf-dump-key>queue</span>" => <span class=sf-dump-note>array:3</span> [<samp>
                  "<span class=sf-dump-key>default</span>" => "<span class=sf-dump-str title="4 characters">sync</span>"
                  "<span class=sf-dump-key>connections</span>" => <span class=sf-dump-note>array:5</span> [<samp>
                    "<span class=sf-dump-key>sync</span>" => <span class=sf-dump-note>array:1</span> [ &hellip;1]
                    "<span class=sf-dump-key>database</span>" => <span class=sf-dump-note>array:4</span> [ &hellip;4]
                    "<span class=sf-dump-key>beanstalkd</span>" => <span class=sf-dump-note>array:5</span> [ &hellip;5]
                    "<span class=sf-dump-key>sqs</span>" => <span class=sf-dump-note>array:6</span> [ &hellip;6]
                    "<span class=sf-dump-key>redis</span>" => <span class=sf-dump-note>array:5</span> [ &hellip;5]
                  </samp>]
                  "<span class=sf-dump-key>failed</span>" => <span class=sf-dump-note>array:3</span> [<samp>
                    "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="8 characters">database</span>"
                    "<span class=sf-dump-key>database</span>" => "<span class=sf-dump-str title="5 characters">mysql</span>"
                    "<span class=sf-dump-key>table</span>" => "<span class=sf-dump-str title="11 characters">failed_jobs</span>"
                  </samp>]
                </samp>]
                "<span class=sf-dump-key>services</span>" => <span class=sf-dump-note>array:3</span> [<samp>
                  "<span class=sf-dump-key>mailgun</span>" => <span class=sf-dump-note>array:3</span> [<samp>
                    "<span class=sf-dump-key>domain</span>" => <span class=sf-dump-const>null</span>
                    "<span class=sf-dump-key>secret</span>" => <span class=sf-dump-const>null</span>
                    "<span class=sf-dump-key>endpoint</span>" => "<span class=sf-dump-str title="15 characters">api.mailgun.net</span>"
                  </samp>]
                  "<span class=sf-dump-key>postmark</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                    "<span class=sf-dump-key>token</span>" => <span class=sf-dump-const>null</span>
                  </samp>]
                  "<span class=sf-dump-key>ses</span>" => <span class=sf-dump-note>array:3</span> [<samp>
                    "<span class=sf-dump-key>key</span>" => ""
                    "<span class=sf-dump-key>secret</span>" => ""
                    "<span class=sf-dump-key>region</span>" => "<span class=sf-dump-str title="9 characters">us-east-1</span>"
                  </samp>]
                </samp>]
                "<span class=sf-dump-key>session</span>" => <span class=sf-dump-note>array:15</span> [<samp>
                  "<span class=sf-dump-key>driver</span>" => "<span class=sf-dump-str title="4 characters">file</span>"
                  "<span class=sf-dump-key>lifetime</span>" => "<span class=sf-dump-str title="3 characters">120</span>"
                  "<span class=sf-dump-key>expire_on_close</span>" => <span class=sf-dump-const>false</span>
                  "<span class=sf-dump-key>encrypt</span>" => <span class=sf-dump-const>false</span>
                  "<span class=sf-dump-key>files</span>" => "<span class=sf-dump-str title="89 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\storage\framework/sessions</span>"
                  "<span class=sf-dump-key>connection</span>" => <span class=sf-dump-const>null</span>
                  "<span class=sf-dump-key>table</span>" => "<span class=sf-dump-str title="8 characters">sessions</span>"
                  "<span class=sf-dump-key>store</span>" => <span class=sf-dump-const>null</span>
                  "<span class=sf-dump-key>lottery</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                    <span class=sf-dump-index>0</span> => <span class=sf-dump-num>2</span>
                    <span class=sf-dump-index>1</span> => <span class=sf-dump-num>100</span>
                  </samp>]
                  "<span class=sf-dump-key>cookie</span>" => "<span class=sf-dump-str title="22 characters">grower_lab_app_session</span>"
                  "<span class=sf-dump-key>path</span>" => "<span class=sf-dump-str>/</span>"
                  "<span class=sf-dump-key>domain</span>" => <span class=sf-dump-const>null</span>
                  "<span class=sf-dump-key>secure</span>" => <span class=sf-dump-const>false</span>
                  "<span class=sf-dump-key>http_only</span>" => <span class=sf-dump-const>true</span>
                  "<span class=sf-dump-key>same_site</span>" => <span class=sf-dump-const>null</span>
                </samp>]
                "<span class=sf-dump-key>view</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                  "<span class=sf-dump-key>paths</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                    <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="78 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\resources\views</span>"
                  </samp>]
                  "<span class=sf-dump-key>compiled</span>" => "<span class=sf-dump-str title="86 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\storage\framework\views</span>"
                </samp>]
                "<span class=sf-dump-key>flare</span>" => <span class=sf-dump-note>array:3</span> [<samp>
                  "<span class=sf-dump-key>key</span>" => <span class=sf-dump-const>null</span>
                  "<span class=sf-dump-key>reporting</span>" => <span class=sf-dump-note>array:7</span> [<samp>
                    "<span class=sf-dump-key>anonymize_ips</span>" => <span class=sf-dump-const>true</span>
                    "<span class=sf-dump-key>collect_git_information</span>" => <span class=sf-dump-const>true</span>
                    "<span class=sf-dump-key>report_queries</span>" => <span class=sf-dump-const>true</span>
                    "<span class=sf-dump-key>maximum_number_of_collected_queries</span>" => <span class=sf-dump-num>200</span>
                    "<span class=sf-dump-key>report_query_bindings</span>" => <span class=sf-dump-const>true</span>
                    "<span class=sf-dump-key>report_view_data</span>" => <span class=sf-dump-const>true</span>
                    "<span class=sf-dump-key>grouping_type</span>" => <span class=sf-dump-const>null</span>
                  </samp>]
                  "<span class=sf-dump-key>send_logs_as_events</span>" => <span class=sf-dump-const>true</span>
                </samp>]
                "<span class=sf-dump-key>ignition</span>" => <span class=sf-dump-note>array:9</span> [<samp>
                  "<span class=sf-dump-key>editor</span>" => "<span class=sf-dump-str title="8 characters">phpstorm</span>"
                  "<span class=sf-dump-key>theme</span>" => "<span class=sf-dump-str title="5 characters">light</span>"
                  "<span class=sf-dump-key>enable_share_button</span>" => <span class=sf-dump-const>true</span>
                  "<span class=sf-dump-key>register_commands</span>" => <span class=sf-dump-const>false</span>
                  "<span class=sf-dump-key>ignored_solution_providers</span>" => []
                  "<span class=sf-dump-key>enable_runnable_solutions</span>" => <span class=sf-dump-const>null</span>
                  "<span class=sf-dump-key>remote_sites_path</span>" => ""
                  "<span class=sf-dump-key>local_sites_path</span>" => ""
                  "<span class=sf-dump-key>housekeeping_endpoint_prefix</span>" => "<span class=sf-dump-str title="9 characters">_ignition</span>"
                </samp>]
                "<span class=sf-dump-key>trustedproxy</span>" => <span class=sf-dump-note>array:2</span> [<samp>
                  "<span class=sf-dump-key>proxies</span>" => <span class=sf-dump-const>null</span>
                  "<span class=sf-dump-key>headers</span>" => <span class=sf-dump-num>30</span>
                </samp>]
              </samp>]
            </samp>}
            "<span class=sf-dump-key>Facade\Ignition\LogRecorder\LogRecorder</span>" => <span class=sf-dump-note title="Facade\Ignition\LogRecorder\LogRecorder
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\LogRecorder</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>LogRecorder</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2106 title="3 occurrences">#106</a><samp id=sf-dump-1538836868-ref2106>
              #<span class=sf-dump-protected title="Protected property">logMessages</span>: []
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            "<span class=sf-dump-key>Facade\Ignition\DumpRecorder\DumpRecorder</span>" => <span class=sf-dump-note title="Facade\Ignition\DumpRecorder\DumpRecorder
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\DumpRecorder</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>DumpRecorder</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2104 title="2 occurrences">#104</a><samp id=sf-dump-1538836868-ref2104>
              #<span class=sf-dump-protected title="Protected property">dumps</span>: []
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            "<span class=sf-dump-key>Facade\Ignition\QueryRecorder\QueryRecorder</span>" => <span class=sf-dump-note title="Facade\Ignition\QueryRecorder\QueryRecorder
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\QueryRecorder</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueryRecorder</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2107 title="3 occurrences">#107</a><samp id=sf-dump-1538836868-ref2107>
              #<span class=sf-dump-protected title="Protected property">queries</span>: []
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            "<span class=sf-dump-key>flare.http</span>" => <span class=sf-dump-note title="Facade\FlareClient\Http\Client
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\FlareClient\Http</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Client</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2111 title="3 occurrences">#111</a><samp id=sf-dump-1538836868-ref2111>
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Http\Client`">apiToken</span>: <span class=sf-dump-const>null</span>
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Http\Client`">apiSecret</span>: <span class=sf-dump-const>null</span>
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Http\Client`">baseUrl</span>: "<span class=sf-dump-str title="23 characters">https://flareapp.io/api</span>"
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Http\Client`">timeout</span>: <span class=sf-dump-num>10</span>
            </samp>}
            "<span class=sf-dump-key>flare.client</span>" => <span class=sf-dump-note title="Facade\FlareClient\Flare
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\FlareClient</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Flare</span> {<a class=sf-dump-ref>#112</a><samp>
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Flare`">client</span>: <span class=sf-dump-note title="Facade\FlareClient\Http\Client
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\FlareClient\Http</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Client</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2111 title="3 occurrences">#111</a>}
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Flare`">api</span>: <span class=sf-dump-note title="Facade\FlareClient\Api
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\FlareClient</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Api</span> {<a class=sf-dump-ref>#113</a><samp>
                -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Api`">client</span>: <span class=sf-dump-note title="Facade\FlareClient\Http\Client
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\FlareClient\Http</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Client</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2111 title="3 occurrences">#111</a>}
                -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Api`">queue</span>: []
              </samp>}
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Flare`">middleware</span>: <span class=sf-dump-note>array:9</span> [<samp>
                <span class=sf-dump-index>0</span> => <span class=sf-dump-note title="Facade\FlareClient\Middleware\AddGlows
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\FlareClient\Middleware</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AddGlows</span> {<a class=sf-dump-ref>#114</a><samp>
                  -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Middleware\AddGlows`">recorder</span>: <span class=sf-dump-note title="Facade\FlareClient\Glows\Recorder
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\FlareClient\Glows</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Recorder</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2109 title="2 occurrences">#109</a><samp id=sf-dump-1538836868-ref2109>
                    -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Glows\Recorder`">glows</span>: []
                  </samp>}
                </samp>}
                <span class=sf-dump-index>1</span> => <span class=sf-dump-note title="Facade\FlareClient\Middleware\AnonymizeIp
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\FlareClient\Middleware</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AnonymizeIp</span> {<a class=sf-dump-ref>#115</a>}
                <span class=sf-dump-index>2</span> => <span class=sf-dump-note title="Facade\Ignition\Middleware\SetNotifierName
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\Middleware</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>SetNotifierName</span> {<a class=sf-dump-ref>#119</a>}
                <span class=sf-dump-index>3</span> => <span class=sf-dump-note title="Facade\Ignition\Middleware\AddEnvironmentInformation
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\Middleware</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AddEnvironmentInformation</span> {<a class=sf-dump-ref>#120</a>}
                <span class=sf-dump-index>4</span> => <span class=sf-dump-note title="Facade\Ignition\Middleware\AddLogs
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\Middleware</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AddLogs</span> {<a class=sf-dump-ref>#123</a><samp>
                  #<span class=sf-dump-protected title="Protected property">logRecorder</span>: <span class=sf-dump-note title="Facade\Ignition\LogRecorder\LogRecorder
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\LogRecorder</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>LogRecorder</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2106 title="3 occurrences">#106</a>}
                </samp>}
                <span class=sf-dump-index>5</span> => <span class=sf-dump-note title="Facade\Ignition\Middleware\AddDumps
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\Middleware</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AddDumps</span> {<a class=sf-dump-ref>#124</a><samp>
                  #<span class=sf-dump-protected title="Protected property">dumpRecorder</span>: <span class=sf-dump-note title="Facade\Ignition\DumpRecorder\DumpRecorder
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\DumpRecorder</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>DumpRecorder</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2104 title="2 occurrences">#104</a>}
                </samp>}
                <span class=sf-dump-index>6</span> => <span class=sf-dump-note title="Facade\Ignition\Middleware\AddQueries
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\Middleware</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AddQueries</span> {<a class=sf-dump-ref>#125</a><samp>
                  #<span class=sf-dump-protected title="Protected property">queryRecorder</span>: <span class=sf-dump-note title="Facade\Ignition\QueryRecorder\QueryRecorder
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\QueryRecorder</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueryRecorder</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2107 title="3 occurrences">#107</a>}
                </samp>}
                <span class=sf-dump-index>7</span> => <span class=sf-dump-note title="Facade\Ignition\Middleware\AddSolutions
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\Middleware</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AddSolutions</span> {<a class=sf-dump-ref>#128</a><samp>
                  #<span class=sf-dump-protected title="Protected property">solutionProviderRepository</span>: <span class=sf-dump-note title="Facade\Ignition\SolutionProviders\SolutionProviderRepository
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\SolutionProviders</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>SolutionProviderRepository</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2126 title="2 occurrences">#126</a><samp id=sf-dump-1538836868-ref2126>
                    #<span class=sf-dump-protected title="Protected property">solutionProviders</span>: <span class=sf-dump-note title="Illuminate\Support\Collection
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Support</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Collection</span> {<a class=sf-dump-ref>#127</a><samp>
                      #<span class=sf-dump-protected title="Protected property">items</span>: <span class=sf-dump-note>array:14</span> [<samp>
                        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="77 characters">Facade\Ignition\SolutionProviders\IncorrectValetDbCredentialsSolutionProvider</span>"
                        <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="63 characters">Facade\Ignition\SolutionProviders\MissingAppKeySolutionProvider</span>"
                        <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="63 characters">Facade\Ignition\SolutionProviders\DefaultDbNameSolutionProvider</span>"
                        <span class=sf-dump-index>3</span> => "<span class=sf-dump-str title="63 characters">Facade\Ignition\SolutionProviders\BadMethodCallSolutionProvider</span>"
                        <span class=sf-dump-index>4</span> => "<span class=sf-dump-str title="63 characters">Facade\Ignition\SolutionProviders\TableNotFoundSolutionProvider</span>"
                        <span class=sf-dump-index>5</span> => "<span class=sf-dump-str title="63 characters">Facade\Ignition\SolutionProviders\MissingImportSolutionProvider</span>"
                        <span class=sf-dump-index>6</span> => "<span class=sf-dump-str title="64 characters">Facade\Ignition\SolutionProviders\MissingPackageSolutionProvider</span>"
                        <span class=sf-dump-index>7</span> => "<span class=sf-dump-str title="68 characters">Facade\Ignition\SolutionProviders\InvalidRouteActionSolutionProvider</span>"
                        <span class=sf-dump-index>8</span> => "<span class=sf-dump-str title="62 characters">Facade\Ignition\SolutionProviders\ViewNotFoundSolutionProvider</span>"
                        <span class=sf-dump-index>9</span> => "<span class=sf-dump-str title="67 characters">Facade\Ignition\SolutionProviders\UndefinedVariableSolutionProvider</span>"
                        <span class=sf-dump-index>10</span> => "<span class=sf-dump-str title="63 characters">Facade\Ignition\SolutionProviders\MergeConflictSolutionProvider</span>"
                        <span class=sf-dump-index>11</span> => "<span class=sf-dump-str title="72 characters">Facade\Ignition\SolutionProviders\RunningLaravelDuskInProductionProvider</span>"
                        <span class=sf-dump-index>12</span> => "<span class=sf-dump-str title="63 characters">Facade\Ignition\SolutionProviders\MissingColumnSolutionProvider</span>"
                        <span class=sf-dump-index>13</span> => "<span class=sf-dump-str title="67 characters">Facade\Ignition\SolutionProviders\UnknownValidationSolutionProvider</span>"
                      </samp>]
                    </samp>}
                  </samp>}
                </samp>}
                <span class=sf-dump-index>8</span> => <span class=sf-dump-note title="Facade\Ignition\Middleware\AddGitInformation
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\Middleware</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AddGitInformation</span> {<a class=sf-dump-ref>#116</a>}
              </samp>]
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Flare`">recorder</span>: <span class=sf-dump-note title="Facade\FlareClient\Glows\Recorder
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\FlareClient\Glows</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Recorder</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2109 title="2 occurrences">#109</a>}
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Flare`">applicationPath</span>: "<span class=sf-dump-str title="62 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app</span>"
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Flare`">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Flare`">contextDetector</span>: <span class=sf-dump-note title="Facade\Ignition\Context\LaravelContextDetector
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\Context</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>LaravelContextDetector</span> {<a class=sf-dump-ref>#110</a>}
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Flare`">previousExceptionHandler</span>: <span class=sf-dump-const>null</span>
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Flare`">previousErrorHandler</span>: <span class=sf-dump-const>null</span>
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Flare`">messageLevel</span>: <span class=sf-dump-const>null</span>
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Flare`">stage</span>: "<span class=sf-dump-str title="5 characters">local</span>"
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Facade\FlareClient\Flare`">userProvidedContext</span>: []
            </samp>}
            "<span class=sf-dump-key>Facade\IgnitionContracts\SolutionProviderRepository</span>" => <span class=sf-dump-note title="Facade\Ignition\SolutionProviders\SolutionProviderRepository
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\SolutionProviders</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>SolutionProviderRepository</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2126 title="2 occurrences">#126</a>}
            "<span class=sf-dump-key>db.factory</span>" => <span class=sf-dump-note title="Illuminate\Database\Connectors\ConnectionFactory
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database\Connectors</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ConnectionFactory</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref244 title="2 occurrences">#44</a> &hellip;}
            "<span class=sf-dump-key>db</span>" => <span class=sf-dump-note title="Illuminate\Database\DatabaseManager
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>DatabaseManager</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref250 title="2 occurrences">#50</a> &hellip;}
            "<span class=sf-dump-key>view.engine.resolver</span>" => <span class=sf-dump-note title="Illuminate\View\Engines\EngineResolver
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\View\Engines</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>EngineResolver</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2136 title="2 occurrences">#136</a><samp id=sf-dump-1538836868-ref2136>
              #<span class=sf-dump-protected title="Protected property">resolvers</span>: <span class=sf-dump-note>array:3</span> [<samp>
                "<span class=sf-dump-key>file</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#137</a><samp>
                  <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\View\ViewServiceProvider
35 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\View</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>ViewServiceProvider</span>"
                  <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\View\ViewServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\View</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ViewServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref291 title="6 occurrences">#91</a>}
                  <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\View\ViewServiceProvider.php
131 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\View\ViewServiceProvider.php</span>"
                  <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">126 to 128</span>"
                </samp>}
                "<span class=sf-dump-key>php</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#144</a><samp>
                  <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Facade\Ignition\IgnitionServiceProvider
39 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>IgnitionServiceProvider</span>"
                  <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Facade\Ignition\IgnitionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>IgnitionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref296 title="10 occurrences">#96</a>}
                  <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\facade\ignition\src\IgnitionServiceProvider.php
117 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\facade\ignition\</span>src\IgnitionServiceProvider.php</span>"
                  <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">121 to 123</span>"
                </samp>}
                "<span class=sf-dump-key>blade</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#138</a><samp>
                  <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Facade\Ignition\IgnitionServiceProvider
39 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>IgnitionServiceProvider</span>"
                  <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Facade\Ignition\IgnitionServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>IgnitionServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref296 title="10 occurrences">#96</a>}
                  <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\facade\ignition\src\IgnitionServiceProvider.php
117 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\facade\ignition\</span>src\IgnitionServiceProvider.php</span>"
                  <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">125 to 127</span>"
                </samp>}
              </samp>]
              #<span class=sf-dump-protected title="Protected property">resolved</span>: []
            </samp>}
            "<span class=sf-dump-key>files</span>" => <span class=sf-dump-note title="Illuminate\Filesystem\Filesystem
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Filesystem</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Filesystem</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2143 title="4 occurrences">#143</a>}
            "<span class=sf-dump-key>blade.compiler</span>" => <span class=sf-dump-note title="Illuminate\View\Compilers\BladeCompiler
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\View\Compilers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>BladeCompiler</span> {<a class=sf-dump-ref>#142</a><samp>
              #<span class=sf-dump-protected title="Protected property">extensions</span>: []
              #<span class=sf-dump-protected title="Protected property">customDirectives</span>: []
              #<span class=sf-dump-protected title="Protected property">conditions</span>: []
              #<span class=sf-dump-protected title="Protected property">path</span>: <span class=sf-dump-const>null</span>
              #<span class=sf-dump-protected title="Protected property">compilers</span>: <span class=sf-dump-note>array:4</span> [<samp>
                <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="8 characters">Comments</span>"
                <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="10 characters">Extensions</span>"
                <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="10 characters">Statements</span>"
                <span class=sf-dump-index>3</span> => "<span class=sf-dump-str title="5 characters">Echos</span>"
              </samp>]
              #<span class=sf-dump-protected title="Protected property">rawTags</span>: <span class=sf-dump-note>array:2</span> [<samp>
                <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">{!!</span>"
                <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="3 characters">!!}</span>"
              </samp>]
              #<span class=sf-dump-protected title="Protected property">contentTags</span>: <span class=sf-dump-note>array:2</span> [<samp>
                <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="2 characters">{{</span>"
                <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="2 characters">}}</span>"
              </samp>]
              #<span class=sf-dump-protected title="Protected property">escapedTags</span>: <span class=sf-dump-note>array:2</span> [<samp>
                <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="3 characters">{{{</span>"
                <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="3 characters">}}}</span>"
              </samp>]
              #<span class=sf-dump-protected title="Protected property">echoFormat</span>: "<span class=sf-dump-str title="5 characters">e(%s)</span>"
              #<span class=sf-dump-protected title="Protected property">footer</span>: []
              #<span class=sf-dump-protected title="Protected property">rawBlocks</span>: []
              #<span class=sf-dump-protected title="Protected property">files</span>: <span class=sf-dump-note title="Illuminate\Filesystem\Filesystem
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Filesystem</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Filesystem</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2143 title="4 occurrences">#143</a>}
              #<span class=sf-dump-protected title="Protected property">cachePath</span>: "<span class=sf-dump-str title="86 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\storage\framework\views</span>"
              #<span class=sf-dump-protected title="Protected property">firstCaseInSwitch</span>: <span class=sf-dump-const>true</span>
              -<span class=sf-dump-private title="Private property defined in class:&#10;`Illuminate\View\Compilers\BladeCompiler`">encodingOptions</span>: <span class=sf-dump-num>15</span>
              #<span class=sf-dump-protected title="Protected property">lastSection</span>: <span class=sf-dump-const>null</span>
              #<span class=sf-dump-protected title="Protected property">forElseCounter</span>: <span class=sf-dump-num>0</span>
            </samp>}
            "<span class=sf-dump-key>log</span>" => <span class=sf-dump-note title="Illuminate\Log\LogManager
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Log</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>LogManager</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2148 title="2 occurrences">#148</a><samp id=sf-dump-1538836868-ref2148>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
              #<span class=sf-dump-protected title="Protected property">channels</span>: []
              #<span class=sf-dump-protected title="Protected property">customCreators</span>: <span class=sf-dump-note>array:1</span> [<samp>
                "<span class=sf-dump-key>flare</span>" => <span class=sf-dump-note>Closure($app)</span> {<a class=sf-dump-ref>#150</a><samp>
                  <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Log\LogManager
25 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Log</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>LogManager</span>"
                  <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Log\LogManager
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Log</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>LogManager</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2148 title="2 occurrences">#148</a>}
                  <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\facade\ignition\src\IgnitionServiceProvider.php
117 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\facade\ignition\</span>src\IgnitionServiceProvider.php</span>"
                  <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">251 to 253</span>"
                </samp>}
              </samp>]
              #<span class=sf-dump-protected title="Protected property">dateFormat</span>: "<span class=sf-dump-str title="11 characters">Y-m-d H:i:s</span>"
              #<span class=sf-dump-protected title="Protected property">levels</span>: <span class=sf-dump-note>array:8</span> [<samp>
                "<span class=sf-dump-key>debug</span>" => <span class=sf-dump-num>100</span>
                "<span class=sf-dump-key>info</span>" => <span class=sf-dump-num>200</span>
                "<span class=sf-dump-key>notice</span>" => <span class=sf-dump-num>250</span>
                "<span class=sf-dump-key>warning</span>" => <span class=sf-dump-num>300</span>
                "<span class=sf-dump-key>error</span>" => <span class=sf-dump-num>400</span>
                "<span class=sf-dump-key>critical</span>" => <span class=sf-dump-num>500</span>
                "<span class=sf-dump-key>alert</span>" => <span class=sf-dump-num>550</span>
                "<span class=sf-dump-key>emergency</span>" => <span class=sf-dump-num>600</span>
              </samp>]
            </samp>}
            "<span class=sf-dump-key>queue</span>" => <span class=sf-dump-note title="Illuminate\Queue\QueueManager
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueueManager</span> {<a class=sf-dump-ref>#160</a><samp>
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
              #<span class=sf-dump-protected title="Protected property">connections</span>: []
              #<span class=sf-dump-protected title="Protected property">connectors</span>: <span class=sf-dump-note>array:6</span> [<samp>
                "<span class=sf-dump-key>null</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#162</a><samp>
                  <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Queue\QueueServiceProvider
37 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>QueueServiceProvider</span>"
                  <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Queue\QueueServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueueServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2152 title="13 occurrences">#152</a>}
                  <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Queue\QueueServiceProvider.php
133 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Queue\QueueServiceProvider.php</span>"
                  <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">89 to 91</span>"
                </samp>}
                "<span class=sf-dump-key>sync</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#163</a><samp>
                  <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Queue\QueueServiceProvider
37 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>QueueServiceProvider</span>"
                  <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Queue\QueueServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueueServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2152 title="13 occurrences">#152</a>}
                  <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Queue\QueueServiceProvider.php
133 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Queue\QueueServiceProvider.php</span>"
                  <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">102 to 104</span>"
                </samp>}
                "<span class=sf-dump-key>database</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#164</a><samp>
                  <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Queue\QueueServiceProvider
37 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>QueueServiceProvider</span>"
                  <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Queue\QueueServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueueServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2152 title="13 occurrences">#152</a>}
                  <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Queue\QueueServiceProvider.php
133 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Queue\QueueServiceProvider.php</span>"
                  <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">115 to 117</span>"
                </samp>}
                "<span class=sf-dump-key>redis</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#165</a><samp>
                  <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Queue\QueueServiceProvider
37 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>QueueServiceProvider</span>"
                  <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Queue\QueueServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueueServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2152 title="13 occurrences">#152</a>}
                  <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Queue\QueueServiceProvider.php
133 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Queue\QueueServiceProvider.php</span>"
                  <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">128 to 130</span>"
                </samp>}
                "<span class=sf-dump-key>beanstalkd</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#166</a><samp>
                  <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Queue\QueueServiceProvider
37 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>QueueServiceProvider</span>"
                  <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Queue\QueueServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueueServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2152 title="13 occurrences">#152</a>}
                  <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Queue\QueueServiceProvider.php
133 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Queue\QueueServiceProvider.php</span>"
                  <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">141 to 143</span>"
                </samp>}
                "<span class=sf-dump-key>sqs</span>" => <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#167</a><samp>
                  <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Queue\QueueServiceProvider
37 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>QueueServiceProvider</span>"
                  <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Queue\QueueServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Queue</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueueServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2152 title="13 occurrences">#152</a>}
                  <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Queue\QueueServiceProvider.php
133 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Queue\QueueServiceProvider.php</span>"
                  <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">154 to 156</span>"
                </samp>}
              </samp>]
            </samp>}
            "<span class=sf-dump-key>date</span>" => <span class=sf-dump-note title="Illuminate\Support\DateFactory
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Support</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>DateFactory</span> {<a class=sf-dump-ref>#182</a>}
            "<span class=sf-dump-key>routes</span>" => <span class=sf-dump-note title="Illuminate\Routing\RouteCollection
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RouteCollection</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref237 title="3 occurrences">#37</a>}
            "<span class=sf-dump-key>url</span>" => <span class=sf-dump-note title="Illuminate\Routing\UrlGenerator
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>UrlGenerator</span> {<a class=sf-dump-ref>#186</a><samp>
              #<span class=sf-dump-protected title="Protected property">routes</span>: <span class=sf-dump-note title="Illuminate\Routing\RouteCollection
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RouteCollection</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref237 title="3 occurrences">#37</a>}
              #<span class=sf-dump-protected title="Protected property">request</span>: <span class=sf-dump-note title="Illuminate\Http\Request
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Http</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Request</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref251 title="3 occurrences">#51</a>}
              #<span class=sf-dump-protected title="Protected property">assetRoot</span>: <span class=sf-dump-const>null</span>
              #<span class=sf-dump-protected title="Protected property">forcedRoot</span>: <span class=sf-dump-const>null</span>
              #<span class=sf-dump-protected title="Protected property">forceScheme</span>: <span class=sf-dump-const>null</span>
              #<span class=sf-dump-protected title="Protected property">cachedRoot</span>: <span class=sf-dump-const>null</span>
              #<span class=sf-dump-protected title="Protected property">cachedScheme</span>: <span class=sf-dump-const>null</span>
              #<span class=sf-dump-protected title="Protected property">rootNamespace</span>: "<span class=sf-dump-str title="20 characters">App\Http\Controllers</span>"
              #<span class=sf-dump-protected title="Protected property">sessionResolver</span>: <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#188</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Routing\RoutingServiceProvider
41 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>RoutingServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Routing\RoutingServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RoutingServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref218 title="13 occurrences">#18</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Routing\RoutingServiceProvider.php
137 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Routing\RoutingServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">76 to 78</span>"
              </samp>}
              #<span class=sf-dump-protected title="Protected property">keyResolver</span>: <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#189</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Routing\RoutingServiceProvider
41 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>RoutingServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Routing\RoutingServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RoutingServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref218 title="13 occurrences">#18</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Routing\RoutingServiceProvider.php
137 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Routing\RoutingServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">80 to 82</span>"
              </samp>}
              #<span class=sf-dump-protected title="Protected property">formatHostUsing</span>: <span class=sf-dump-const>null</span>
              #<span class=sf-dump-protected title="Protected property">formatPathUsing</span>: <span class=sf-dump-const>null</span>
              #<span class=sf-dump-protected title="Protected property">routeGenerator</span>: <span class=sf-dump-const>null</span>
            </samp>}
            "<span class=sf-dump-key>Illuminate\Routing\Route</span>" => <span class=sf-dump-note title="Illuminate\Routing\Route
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Route</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2208 title="6 occurrences">#208</a>}
            "<span class=sf-dump-key>Illuminate\Routing\Contracts\ControllerDispatcher</span>" => <span class=sf-dump-note title="Illuminate\Routing\ControllerDispatcher
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ControllerDispatcher</span> {<a class=sf-dump-ref>#227</a><samp>
              #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
            </samp>}
            "<span class=sf-dump-key>encrypter</span>" => <span class=sf-dump-note title="Illuminate\Encryption\Encrypter
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Encryption</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Encrypter</span> {<a class=sf-dump-ref>#247</a><samp>
              #<span class=sf-dump-protected title="Protected property">key</span>: b"<span class=sf-dump-str title="32 binary or non-UTF-8 characters">u&Euml;&sup3;&nbsp;&#9474;3i<span class="sf-dump-default">\x1F</span>&auml;&AElig;&#9524;K&quot;&pound;&gt;&acirc;&lt;&euml;&igrave;&deg;&#9553;}k&#9608;&Auml;<span class="sf-dump-default">\e</span>&ETH;&aacute;7ZD<span class="sf-dump-default">\x17</span></span>"
              #<span class=sf-dump-protected title="Protected property">cipher</span>: "<span class=sf-dump-str title="11 characters">AES-256-CBC</span>"
            </samp>}
            "<span class=sf-dump-key>cookie</span>" => <span class=sf-dump-note title="Illuminate\Cookie\CookieJar
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Cookie</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>CookieJar</span> {<a class=sf-dump-ref>#249</a><samp>
              #<span class=sf-dump-protected title="Protected property">path</span>: "<span class=sf-dump-str>/</span>"
              #<span class=sf-dump-protected title="Protected property">domain</span>: <span class=sf-dump-const>null</span>
              #<span class=sf-dump-protected title="Protected property">secure</span>: <span class=sf-dump-const>false</span>
              #<span class=sf-dump-protected title="Protected property">sameSite</span>: <span class=sf-dump-const>null</span>
              #<span class=sf-dump-protected title="Protected property">queued</span>: []
            </samp>}
            "<span class=sf-dump-key>session</span>" => <span class=sf-dump-note title="Illuminate\Session\SessionManager
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Session</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>SessionManager</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2251 title="2 occurrences">#251</a><samp id=sf-dump-1538836868-ref2251>
              #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
              #<span class=sf-dump-protected title="Protected property">app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
              #<span class=sf-dump-protected title="Protected property">config</span>: <span class=sf-dump-note title="Illuminate\Config\Repository
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Config</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Repository</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref242 title="2 occurrences">#42</a>}
              #<span class=sf-dump-protected title="Protected property">customCreators</span>: []
              #<span class=sf-dump-protected title="Protected property">drivers</span>: <span class=sf-dump-note>array:1</span> [<samp>
                "<span class=sf-dump-key>file</span>" => <span class=sf-dump-note title="Illuminate\Session\Store
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Session</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Store</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2246 title="2 occurrences">#246</a>}
              </samp>]
            </samp>}
            "<span class=sf-dump-key>Illuminate\Session\Middleware\StartSession</span>" => <span class=sf-dump-note title="Illuminate\Session\Middleware\StartSession
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Session\Middleware</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>StartSession</span> {<a class=sf-dump-ref>#252</a><samp>
              #<span class=sf-dump-protected title="Protected property">manager</span>: <span class=sf-dump-note title="Illuminate\Session\SessionManager
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Session</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>SessionManager</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2251 title="2 occurrences">#251</a>}
            </samp>}
            "<span class=sf-dump-key>view</span>" => <span class=sf-dump-note title="Illuminate\View\Factory
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\View</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Factory</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2256 title="2 occurrences">#256</a><samp id=sf-dump-1538836868-ref2256>
              #<span class=sf-dump-protected title="Protected property">engines</span>: <span class=sf-dump-note title="Illuminate\View\Engines\EngineResolver
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\View\Engines</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>EngineResolver</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2136 title="2 occurrences">#136</a>}
              #<span class=sf-dump-protected title="Protected property">finder</span>: <span class=sf-dump-note title="Illuminate\View\FileViewFinder
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\View</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>FileViewFinder</span> {<a class=sf-dump-ref>#254</a><samp>
                #<span class=sf-dump-protected title="Protected property">files</span>: <span class=sf-dump-note title="Illuminate\Filesystem\Filesystem
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Filesystem</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Filesystem</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2143 title="4 occurrences">#143</a>}
                #<span class=sf-dump-protected title="Protected property">paths</span>: <span class=sf-dump-note>array:1</span> [<samp>
                  <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="78 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\resources\views</span>"
                </samp>]
                #<span class=sf-dump-protected title="Protected property">views</span>: []
                #<span class=sf-dump-protected title="Protected property">hints</span>: <span class=sf-dump-note>array:2</span> [<samp>
                  "<span class=sf-dump-key>notifications</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                    <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="132 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Notifications/resources/views</span>"
                  </samp>]
                  "<span class=sf-dump-key>pagination</span>" => <span class=sf-dump-note>array:1</span> [<samp>
                    <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="129 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Pagination/resources/views</span>"
                  </samp>]
                </samp>]
                #<span class=sf-dump-protected title="Protected property">extensions</span>: <span class=sf-dump-note>array:4</span> [<samp>
                  <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="9 characters">blade.php</span>"
                  <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="3 characters">php</span>"
                  <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="3 characters">css</span>"
                  <span class=sf-dump-index>3</span> => "<span class=sf-dump-str title="4 characters">html</span>"
                </samp>]
              </samp>}
              #<span class=sf-dump-protected title="Protected property">events</span>: <span class=sf-dump-note title="Illuminate\Events\Dispatcher
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Dispatcher</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref235 title="9 occurrences">#35</a>}
              #<span class=sf-dump-protected title="Protected property">container</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
              #<span class=sf-dump-protected title="Protected property">shared</span>: <span class=sf-dump-note>array:3</span> [<samp>
                "<span class=sf-dump-key>__env</span>" => <span class=sf-dump-note title="Illuminate\View\Factory
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\View</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Factory</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2256 title="2 occurrences">#256</a>}
                "<span class=sf-dump-key>app</span>" => <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
                "<span class=sf-dump-key>errors</span>" => <span class=sf-dump-note title="Illuminate\Support\ViewErrorBag
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Support</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>ViewErrorBag</span> {<a class=sf-dump-ref>#255</a><samp>
                  #<span class=sf-dump-protected title="Protected property">bags</span>: []
                </samp>}
              </samp>]
              #<span class=sf-dump-protected title="Protected property">extensions</span>: <span class=sf-dump-note>array:4</span> [<samp>
                "<span class=sf-dump-key>blade.php</span>" => "<span class=sf-dump-str title="5 characters">blade</span>"
                "<span class=sf-dump-key>php</span>" => "<span class=sf-dump-str title="3 characters">php</span>"
                "<span class=sf-dump-key>css</span>" => "<span class=sf-dump-str title="4 characters">file</span>"
                "<span class=sf-dump-key>html</span>" => "<span class=sf-dump-str title="4 characters">file</span>"
              </samp>]
              #<span class=sf-dump-protected title="Protected property">composers</span>: []
              #<span class=sf-dump-protected title="Protected property">renderCount</span>: <span class=sf-dump-num>0</span>
              #<span class=sf-dump-protected title="Protected property">componentStack</span>: []
              #<span class=sf-dump-protected title="Protected property">componentData</span>: []
              #<span class=sf-dump-protected title="Protected property">slots</span>: []
              #<span class=sf-dump-protected title="Protected property">slotStack</span>: []
              #<span class=sf-dump-protected title="Protected property">sections</span>: []
              #<span class=sf-dump-protected title="Protected property">sectionStack</span>: []
              #<span class=sf-dump-protected title="Protected property">loopsStack</span>: []
              #<span class=sf-dump-protected title="Protected property">pushes</span>: []
              #<span class=sf-dump-protected title="Protected property">prepends</span>: []
              #<span class=sf-dump-protected title="Protected property">pushStack</span>: []
              #<span class=sf-dump-protected title="Protected property">translationReplacements</span>: []
            </samp>}
          </samp>]
          #<span class=sf-dump-protected title="Protected property">aliases</span>: <span class=sf-dump-note>array:75</span> [<samp>
            "<span class=sf-dump-key>Illuminate\Foundation\Application</span>" => "<span class=sf-dump-str title="3 characters">app</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Container\Container</span>" => "<span class=sf-dump-str title="3 characters">app</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Foundation\Application</span>" => "<span class=sf-dump-str title="3 characters">app</span>"
            "<span class=sf-dump-key>Psr\Container\ContainerInterface</span>" => "<span class=sf-dump-str title="3 characters">app</span>"
            "<span class=sf-dump-key>Illuminate\Auth\AuthManager</span>" => "<span class=sf-dump-str title="4 characters">auth</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Auth\Factory</span>" => "<span class=sf-dump-str title="4 characters">auth</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Auth\Guard</span>" => "<span class=sf-dump-str title="11 characters">auth.driver</span>"
            "<span class=sf-dump-key>Illuminate\View\Compilers\BladeCompiler</span>" => "<span class=sf-dump-str title="14 characters">blade.compiler</span>"
            "<span class=sf-dump-key>Illuminate\Cache\CacheManager</span>" => "<span class=sf-dump-str title="5 characters">cache</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Cache\Factory</span>" => "<span class=sf-dump-str title="5 characters">cache</span>"
            "<span class=sf-dump-key>Illuminate\Cache\Repository</span>" => "<span class=sf-dump-str title="11 characters">cache.store</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Cache\Repository</span>" => "<span class=sf-dump-str title="11 characters">cache.store</span>"
            "<span class=sf-dump-key>Psr\SimpleCache\CacheInterface</span>" => "<span class=sf-dump-str title="11 characters">cache.store</span>"
            "<span class=sf-dump-key>Symfony\Component\Cache\Adapter\Psr16Adapter</span>" => "<span class=sf-dump-str title="10 characters">cache.psr6</span>"
            "<span class=sf-dump-key>Symfony\Component\Cache\Adapter\AdapterInterface</span>" => "<span class=sf-dump-str title="10 characters">cache.psr6</span>"
            "<span class=sf-dump-key>Psr\Cache\CacheItemPoolInterface</span>" => "<span class=sf-dump-str title="10 characters">cache.psr6</span>"
            "<span class=sf-dump-key>Illuminate\Config\Repository</span>" => "<span class=sf-dump-str title="6 characters">config</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Config\Repository</span>" => "<span class=sf-dump-str title="6 characters">config</span>"
            "<span class=sf-dump-key>Illuminate\Cookie\CookieJar</span>" => "<span class=sf-dump-str title="6 characters">cookie</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Cookie\Factory</span>" => "<span class=sf-dump-str title="6 characters">cookie</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Cookie\QueueingFactory</span>" => "<span class=sf-dump-str title="6 characters">cookie</span>"
            "<span class=sf-dump-key>Illuminate\Encryption\Encrypter</span>" => "<span class=sf-dump-str title="9 characters">encrypter</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Encryption\Encrypter</span>" => "<span class=sf-dump-str title="9 characters">encrypter</span>"
            "<span class=sf-dump-key>Illuminate\Database\DatabaseManager</span>" => "<span class=sf-dump-str title="2 characters">db</span>"
            "<span class=sf-dump-key>Illuminate\Database\ConnectionResolverInterface</span>" => "<span class=sf-dump-str title="2 characters">db</span>"
            "<span class=sf-dump-key>Illuminate\Database\Connection</span>" => "<span class=sf-dump-str title="13 characters">db.connection</span>"
            "<span class=sf-dump-key>Illuminate\Database\ConnectionInterface</span>" => "<span class=sf-dump-str title="13 characters">db.connection</span>"
            "<span class=sf-dump-key>Illuminate\Events\Dispatcher</span>" => "<span class=sf-dump-str title="6 characters">events</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Events\Dispatcher</span>" => "<span class=sf-dump-str title="6 characters">events</span>"
            "<span class=sf-dump-key>Illuminate\Filesystem\Filesystem</span>" => "<span class=sf-dump-str title="5 characters">files</span>"
            "<span class=sf-dump-key>Illuminate\Filesystem\FilesystemManager</span>" => "<span class=sf-dump-str title="10 characters">filesystem</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Filesystem\Factory</span>" => "<span class=sf-dump-str title="10 characters">filesystem</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Filesystem\Filesystem</span>" => "<span class=sf-dump-str title="15 characters">filesystem.disk</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Filesystem\Cloud</span>" => "<span class=sf-dump-str title="16 characters">filesystem.cloud</span>"
            "<span class=sf-dump-key>Illuminate\Hashing\HashManager</span>" => "<span class=sf-dump-str title="4 characters">hash</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Hashing\Hasher</span>" => "<span class=sf-dump-str title="11 characters">hash.driver</span>"
            "<span class=sf-dump-key>Illuminate\Translation\Translator</span>" => "<span class=sf-dump-str title="10 characters">translator</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Translation\Translator</span>" => "<span class=sf-dump-str title="10 characters">translator</span>"
            "<span class=sf-dump-key>Illuminate\Log\LogManager</span>" => "<span class=sf-dump-str title="3 characters">log</span>"
            "<span class=sf-dump-key>Psr\Log\LoggerInterface</span>" => "<span class=sf-dump-str title="3 characters">log</span>"
            "<span class=sf-dump-key>Illuminate\Mail\Mailer</span>" => "<span class=sf-dump-str title="6 characters">mailer</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Mail\Mailer</span>" => "<span class=sf-dump-str title="6 characters">mailer</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Mail\MailQueue</span>" => "<span class=sf-dump-str title="6 characters">mailer</span>"
            "<span class=sf-dump-key>Illuminate\Auth\Passwords\PasswordBrokerManager</span>" => "<span class=sf-dump-str title="13 characters">auth.password</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Auth\PasswordBrokerFactory</span>" => "<span class=sf-dump-str title="13 characters">auth.password</span>"
            "<span class=sf-dump-key>Illuminate\Auth\Passwords\PasswordBroker</span>" => "<span class=sf-dump-str title="20 characters">auth.password.broker</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Auth\PasswordBroker</span>" => "<span class=sf-dump-str title="20 characters">auth.password.broker</span>"
            "<span class=sf-dump-key>Illuminate\Queue\QueueManager</span>" => "<span class=sf-dump-str title="5 characters">queue</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Queue\Factory</span>" => "<span class=sf-dump-str title="5 characters">queue</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Queue\Monitor</span>" => "<span class=sf-dump-str title="5 characters">queue</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Queue\Queue</span>" => "<span class=sf-dump-str title="16 characters">queue.connection</span>"
            "<span class=sf-dump-key>Illuminate\Queue\Failed\FailedJobProviderInterface</span>" => "<span class=sf-dump-str title="12 characters">queue.failer</span>"
            "<span class=sf-dump-key>Illuminate\Routing\Redirector</span>" => "<span class=sf-dump-str title="8 characters">redirect</span>"
            "<span class=sf-dump-key>Illuminate\Redis\RedisManager</span>" => "<span class=sf-dump-str title="5 characters">redis</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Redis\Factory</span>" => "<span class=sf-dump-str title="5 characters">redis</span>"
            "<span class=sf-dump-key>Illuminate\Redis\Connections\Connection</span>" => "<span class=sf-dump-str title="16 characters">redis.connection</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Redis\Connection</span>" => "<span class=sf-dump-str title="16 characters">redis.connection</span>"
            "<span class=sf-dump-key>Illuminate\Http\Request</span>" => "<span class=sf-dump-str title="7 characters">request</span>"
            "<span class=sf-dump-key>Symfony\Component\HttpFoundation\Request</span>" => "<span class=sf-dump-str title="7 characters">request</span>"
            "<span class=sf-dump-key>Illuminate\Routing\Router</span>" => "<span class=sf-dump-str title="6 characters">router</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Routing\Registrar</span>" => "<span class=sf-dump-str title="6 characters">router</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Routing\BindingRegistrar</span>" => "<span class=sf-dump-str title="6 characters">router</span>"
            "<span class=sf-dump-key>Illuminate\Session\SessionManager</span>" => "<span class=sf-dump-str title="7 characters">session</span>"
            "<span class=sf-dump-key>Illuminate\Session\Store</span>" => "<span class=sf-dump-str title="13 characters">session.store</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Session\Session</span>" => "<span class=sf-dump-str title="13 characters">session.store</span>"
            "<span class=sf-dump-key>Illuminate\Routing\UrlGenerator</span>" => "<span class=sf-dump-str title="3 characters">url</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Routing\UrlGenerator</span>" => "<span class=sf-dump-str title="3 characters">url</span>"
            "<span class=sf-dump-key>Illuminate\Validation\Factory</span>" => "<span class=sf-dump-str title="9 characters">validator</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Validation\Factory</span>" => "<span class=sf-dump-str title="9 characters">validator</span>"
            "<span class=sf-dump-key>Illuminate\View\Factory</span>" => "<span class=sf-dump-str title="4 characters">view</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\View\Factory</span>" => "<span class=sf-dump-str title="4 characters">view</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Notifications\Dispatcher</span>" => "<span class=sf-dump-str title="39 characters">Illuminate\Notifications\ChannelManager</span>"
            "<span class=sf-dump-key>Illuminate\Contracts\Notifications\Factory</span>" => "<span class=sf-dump-str title="39 characters">Illuminate\Notifications\ChannelManager</span>"
            "<span class=sf-dump-key>Facade\FlareClient\Http\Client</span>" => "<span class=sf-dump-str title="10 characters">flare.http</span>"
            "<span class=sf-dump-key>Facade\FlareClient\Flare</span>" => "<span class=sf-dump-str title="12 characters">flare.client</span>"
          </samp>]
          #<span class=sf-dump-protected title="Protected property">abstractAliases</span>: <span class=sf-dump-note>array:40</span> [<samp>
            "<span class=sf-dump-key>app</span>" => <span class=sf-dump-note>array:4</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="33 characters">Illuminate\Foundation\Application</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="40 characters">Illuminate\Contracts\Container\Container</span>"
              <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="43 characters">Illuminate\Contracts\Foundation\Application</span>"
              <span class=sf-dump-index>3</span> => "<span class=sf-dump-str title="32 characters">Psr\Container\ContainerInterface</span>"
            </samp>]
            "<span class=sf-dump-key>auth</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="27 characters">Illuminate\Auth\AuthManager</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="33 characters">Illuminate\Contracts\Auth\Factory</span>"
            </samp>]
            "<span class=sf-dump-key>auth.driver</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="31 characters">Illuminate\Contracts\Auth\Guard</span>"
            </samp>]
            "<span class=sf-dump-key>blade.compiler</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="39 characters">Illuminate\View\Compilers\BladeCompiler</span>"
            </samp>]
            "<span class=sf-dump-key>cache</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="29 characters">Illuminate\Cache\CacheManager</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="34 characters">Illuminate\Contracts\Cache\Factory</span>"
            </samp>]
            "<span class=sf-dump-key>cache.store</span>" => <span class=sf-dump-note>array:3</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="27 characters">Illuminate\Cache\Repository</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="37 characters">Illuminate\Contracts\Cache\Repository</span>"
              <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="30 characters">Psr\SimpleCache\CacheInterface</span>"
            </samp>]
            "<span class=sf-dump-key>cache.psr6</span>" => <span class=sf-dump-note>array:3</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="44 characters">Symfony\Component\Cache\Adapter\Psr16Adapter</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="48 characters">Symfony\Component\Cache\Adapter\AdapterInterface</span>"
              <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="32 characters">Psr\Cache\CacheItemPoolInterface</span>"
            </samp>]
            "<span class=sf-dump-key>config</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="28 characters">Illuminate\Config\Repository</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="38 characters">Illuminate\Contracts\Config\Repository</span>"
            </samp>]
            "<span class=sf-dump-key>cookie</span>" => <span class=sf-dump-note>array:3</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="27 characters">Illuminate\Cookie\CookieJar</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="35 characters">Illuminate\Contracts\Cookie\Factory</span>"
              <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="43 characters">Illuminate\Contracts\Cookie\QueueingFactory</span>"
            </samp>]
            "<span class=sf-dump-key>encrypter</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="31 characters">Illuminate\Encryption\Encrypter</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="41 characters">Illuminate\Contracts\Encryption\Encrypter</span>"
            </samp>]
            "<span class=sf-dump-key>db</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="35 characters">Illuminate\Database\DatabaseManager</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="47 characters">Illuminate\Database\ConnectionResolverInterface</span>"
            </samp>]
            "<span class=sf-dump-key>db.connection</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="30 characters">Illuminate\Database\Connection</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="39 characters">Illuminate\Database\ConnectionInterface</span>"
            </samp>]
            "<span class=sf-dump-key>events</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="28 characters">Illuminate\Events\Dispatcher</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="38 characters">Illuminate\Contracts\Events\Dispatcher</span>"
            </samp>]
            "<span class=sf-dump-key>files</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="32 characters">Illuminate\Filesystem\Filesystem</span>"
            </samp>]
            "<span class=sf-dump-key>filesystem</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="39 characters">Illuminate\Filesystem\FilesystemManager</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="39 characters">Illuminate\Contracts\Filesystem\Factory</span>"
            </samp>]
            "<span class=sf-dump-key>filesystem.disk</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="42 characters">Illuminate\Contracts\Filesystem\Filesystem</span>"
            </samp>]
            "<span class=sf-dump-key>filesystem.cloud</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="37 characters">Illuminate\Contracts\Filesystem\Cloud</span>"
            </samp>]
            "<span class=sf-dump-key>hash</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="30 characters">Illuminate\Hashing\HashManager</span>"
            </samp>]
            "<span class=sf-dump-key>hash.driver</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="35 characters">Illuminate\Contracts\Hashing\Hasher</span>"
            </samp>]
            "<span class=sf-dump-key>translator</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="33 characters">Illuminate\Translation\Translator</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="43 characters">Illuminate\Contracts\Translation\Translator</span>"
            </samp>]
            "<span class=sf-dump-key>log</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="25 characters">Illuminate\Log\LogManager</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="23 characters">Psr\Log\LoggerInterface</span>"
            </samp>]
            "<span class=sf-dump-key>mailer</span>" => <span class=sf-dump-note>array:3</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="22 characters">Illuminate\Mail\Mailer</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="32 characters">Illuminate\Contracts\Mail\Mailer</span>"
              <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="35 characters">Illuminate\Contracts\Mail\MailQueue</span>"
            </samp>]
            "<span class=sf-dump-key>auth.password</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="47 characters">Illuminate\Auth\Passwords\PasswordBrokerManager</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="47 characters">Illuminate\Contracts\Auth\PasswordBrokerFactory</span>"
            </samp>]
            "<span class=sf-dump-key>auth.password.broker</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="40 characters">Illuminate\Auth\Passwords\PasswordBroker</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="40 characters">Illuminate\Contracts\Auth\PasswordBroker</span>"
            </samp>]
            "<span class=sf-dump-key>queue</span>" => <span class=sf-dump-note>array:3</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="29 characters">Illuminate\Queue\QueueManager</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="34 characters">Illuminate\Contracts\Queue\Factory</span>"
              <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="34 characters">Illuminate\Contracts\Queue\Monitor</span>"
            </samp>]
            "<span class=sf-dump-key>queue.connection</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="32 characters">Illuminate\Contracts\Queue\Queue</span>"
            </samp>]
            "<span class=sf-dump-key>queue.failer</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="50 characters">Illuminate\Queue\Failed\FailedJobProviderInterface</span>"
            </samp>]
            "<span class=sf-dump-key>redirect</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="29 characters">Illuminate\Routing\Redirector</span>"
            </samp>]
            "<span class=sf-dump-key>redis</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="29 characters">Illuminate\Redis\RedisManager</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="34 characters">Illuminate\Contracts\Redis\Factory</span>"
            </samp>]
            "<span class=sf-dump-key>redis.connection</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="39 characters">Illuminate\Redis\Connections\Connection</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="37 characters">Illuminate\Contracts\Redis\Connection</span>"
            </samp>]
            "<span class=sf-dump-key>request</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="23 characters">Illuminate\Http\Request</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="40 characters">Symfony\Component\HttpFoundation\Request</span>"
            </samp>]
            "<span class=sf-dump-key>router</span>" => <span class=sf-dump-note>array:3</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="25 characters">Illuminate\Routing\Router</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="38 characters">Illuminate\Contracts\Routing\Registrar</span>"
              <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="45 characters">Illuminate\Contracts\Routing\BindingRegistrar</span>"
            </samp>]
            "<span class=sf-dump-key>session</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="33 characters">Illuminate\Session\SessionManager</span>"
            </samp>]
            "<span class=sf-dump-key>session.store</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="24 characters">Illuminate\Session\Store</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="36 characters">Illuminate\Contracts\Session\Session</span>"
            </samp>]
            "<span class=sf-dump-key>url</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="31 characters">Illuminate\Routing\UrlGenerator</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="41 characters">Illuminate\Contracts\Routing\UrlGenerator</span>"
            </samp>]
            "<span class=sf-dump-key>validator</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="29 characters">Illuminate\Validation\Factory</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="39 characters">Illuminate\Contracts\Validation\Factory</span>"
            </samp>]
            "<span class=sf-dump-key>view</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="23 characters">Illuminate\View\Factory</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="33 characters">Illuminate\Contracts\View\Factory</span>"
            </samp>]
            "<span class=sf-dump-key>Illuminate\Notifications\ChannelManager</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="45 characters">Illuminate\Contracts\Notifications\Dispatcher</span>"
              <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="42 characters">Illuminate\Contracts\Notifications\Factory</span>"
            </samp>]
            "<span class=sf-dump-key>flare.http</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="30 characters">Facade\FlareClient\Http\Client</span>"
            </samp>]
            "<span class=sf-dump-key>flare.client</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="24 characters">Facade\FlareClient\Flare</span>"
            </samp>]
          </samp>]
          #<span class=sf-dump-protected title="Protected property">extenders</span>: <span class=sf-dump-note>array:1</span> [<samp>
            "<span class=sf-dump-key>url</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => <span class=sf-dump-note>Closure(UrlGenerator $url, $app)</span> {<a class=sf-dump-ref>#21</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Routing\RoutingServiceProvider
41 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>RoutingServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Routing\RoutingServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RoutingServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref218 title="13 occurrences">#18</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Routing\RoutingServiceProvider.php
137 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Routing\RoutingServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">72 to 92</span>"
              </samp>}
            </samp>]
          </samp>]
          #<span class=sf-dump-protected title="Protected property">tags</span>: []
          #<span class=sf-dump-protected title="Protected property">buildStack</span>: []
          #<span class=sf-dump-protected title="Protected property">with</span>: []
          +<span class=sf-dump-public title="Public property">contextual</span>: []
          #<span class=sf-dump-protected title="Protected property">reboundCallbacks</span>: <span class=sf-dump-note>array:3</span> [<samp>
            "<span class=sf-dump-key>request</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => <span class=sf-dump-note>Closure($app, $request)</span> {<a class=sf-dump-ref>#40</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Auth\AuthServiceProvider
35 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>AuthServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Auth\AuthServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AuthServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref247 title="8 occurrences">#47</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Auth\AuthServiceProvider.php
131 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Auth\AuthServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">82 to 86</span>"
              </samp>}
              <span class=sf-dump-index>1</span> => <span class=sf-dump-note>Closure($app, $request)</span> {<a class=sf-dump-ref>#187</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Routing\RoutingServiceProvider
41 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>RoutingServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Routing\RoutingServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RoutingServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref218 title="13 occurrences">#18</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Routing\RoutingServiceProvider.php
137 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Routing\RoutingServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">102 to 104</span>"
              </samp>}
            </samp>]
            "<span class=sf-dump-key>events</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => <span class=sf-dump-note>Closure($app, $dispatcher)</span> {<a class=sf-dump-ref>#58</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Auth\AuthServiceProvider
35 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>AuthServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Auth\AuthServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Auth</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>AuthServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref247 title="8 occurrences">#47</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Auth\AuthServiceProvider.php
131 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Auth\AuthServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="9 characters">96 to 108</span>"
              </samp>}
            </samp>]
            "<span class=sf-dump-key>routes</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => <span class=sf-dump-note>Closure($app, $routes)</span> {<a class=sf-dump-ref>#190</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Routing\RoutingServiceProvider
41 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>RoutingServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Routing\RoutingServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Routing</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>RoutingServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref218 title="13 occurrences">#18</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Routing\RoutingServiceProvider.php
137 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Routing\RoutingServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">87 to 89</span>"
              </samp>}
            </samp>]
          </samp>]
          #<span class=sf-dump-protected title="Protected property">globalResolvingCallbacks</span>: []
          #<span class=sf-dump-protected title="Protected property">globalAfterResolvingCallbacks</span>: []
          #<span class=sf-dump-protected title="Protected property">resolvingCallbacks</span>: <span class=sf-dump-note>array:1</span> [<samp>
            "<span class=sf-dump-key>Illuminate\Foundation\Http\FormRequest</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => <span class=sf-dump-note>Closure($request, $app)</span> {<a class=sf-dump-ref>#133</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Foundation\Providers\FormRequestServiceProvider
58 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Foundation\Providers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>FormRequestServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Providers\FormRequestServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation\Providers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>FormRequestServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref277 title="4 occurrences">#77</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Foundation\Providers\FormRequestServiceProvider.php
154 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Foundation\Providers\FormRequestServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">33 to 37</span>"
              </samp>}
            </samp>]
          </samp>]
          #<span class=sf-dump-protected title="Protected property">afterResolvingCallbacks</span>: <span class=sf-dump-note>array:2</span> [<samp>
            "<span class=sf-dump-key>Illuminate\Contracts\Validation\ValidatesWhenResolved</span>" => <span class=sf-dump-note>array:1</span> [<samp>
              <span class=sf-dump-index>0</span> => <span class=sf-dump-note>Closure($resolved)</span> {<a class=sf-dump-ref>#132</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Foundation\Providers\FormRequestServiceProvider
58 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Foundation\Providers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>FormRequestServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Foundation\Providers\FormRequestServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation\Providers</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>FormRequestServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref277 title="4 occurrences">#77</a>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Foundation\Providers\FormRequestServiceProvider.php
154 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Foundation\Providers\FormRequestServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">29 to 31</span>"
              </samp>}
            </samp>]
            "<span class=sf-dump-key>view</span>" => <span class=sf-dump-note>array:2</span> [<samp>
              <span class=sf-dump-index>0</span> => <span class=sf-dump-note>Closure($view)</span> {<a class=sf-dump-ref>#134</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Support\ServiceProvider
34 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Support</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>ServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Notifications\NotificationServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Notifications</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>NotificationServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref281 title="3 occurrences">#81</a>}
                <span class=sf-dump-meta>use</span>: {<samp>
                  <span class=sf-dump-meta>$path</span>: "<span class=sf-dump-str title="132 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Notifications/resources/views</span>"
                  <span class=sf-dump-meta>$namespace</span>: "<span class=sf-dump-str title="13 characters">notifications</span>"
                </samp>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Support\ServiceProvider.php
130 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Support\ServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="9 characters">91 to 102</span>"
              </samp>}
              <span class=sf-dump-index>1</span> => <span class=sf-dump-note>Closure($view)</span> {<a class=sf-dump-ref>#135</a><samp>
                <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Support\ServiceProvider
34 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Support</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>ServiceProvider</span>"
                <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Pagination\PaginationServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Pagination</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>PaginationServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref283 title="2 occurrences">#83</a>}
                <span class=sf-dump-meta>use</span>: {<samp>
                  <span class=sf-dump-meta>$path</span>: "<span class=sf-dump-str title="129 characters">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Pagination/resources/views</span>"
                  <span class=sf-dump-meta>$namespace</span>: "<span class=sf-dump-str title="10 characters">pagination</span>"
                </samp>}
                <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Support\ServiceProvider.php
130 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Support\ServiceProvider.php</span>"
                <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="9 characters">91 to 102</span>"
              </samp>}
            </samp>]
          </samp>]
        </samp>}
        #<span class=sf-dump-protected title="Protected property">listeners</span>: <span class=sf-dump-note>array:5</span> [<samp>
          "<span class=sf-dump-key>Illuminate\Queue\Events\Looping</span>" => <span class=sf-dump-note>array:1</span> [<samp>
            <span class=sf-dump-index>0</span> => <span class=sf-dump-note>Closure($event, $payload)</span> {<a class=sf-dump-ref>#168</a><samp>
              <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Events\Dispatcher
28 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Dispatcher</span>"
              <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Events\Dispatcher
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Dispatcher</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref235 title="9 occurrences">#35</a>}
              <span class=sf-dump-meta>use</span>: {<samp>
                <span class=sf-dump-meta>$listener</span>: <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#161</a> &hellip;}
                <span class=sf-dump-meta>$wildcard</span>: <span class=sf-dump-const>false</span>
              </samp>}
              <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Events\Dispatcher.php
124 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Events\Dispatcher.php</span>"
              <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">345 to 351</span>"
            </samp>}
          </samp>]
          "<span class=sf-dump-key>Illuminate\Database\Events\QueryExecuted</span>" => <span class=sf-dump-note>array:1</span> [<samp>
            <span class=sf-dump-index>0</span> => <span class=sf-dump-note>Closure($event, $payload)</span> {<a class=sf-dump-ref>#169</a><samp>
              <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Events\Dispatcher
28 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Dispatcher</span>"
              <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Events\Dispatcher
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Dispatcher</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref235 title="9 occurrences">#35</a>}
              <span class=sf-dump-meta>use</span>: {<samp>
                <span class=sf-dump-meta>$listener</span>: <span class=sf-dump-note>array:2</span> [<samp>
                  <span class=sf-dump-index>0</span> => <span class=sf-dump-note title="Facade\Ignition\QueryRecorder\QueryRecorder
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\QueryRecorder</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>QueryRecorder</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2107 title="3 occurrences">#107</a>}
                  <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="6 characters">record</span>"
                </samp>]
                <span class=sf-dump-meta>$wildcard</span>: <span class=sf-dump-const>false</span>
              </samp>}
              <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Events\Dispatcher.php
124 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Events\Dispatcher.php</span>"
              <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">345 to 351</span>"
            </samp>}
          </samp>]
          "<span class=sf-dump-key>Illuminate\Log\Events\MessageLogged</span>" => <span class=sf-dump-note>array:1</span> [<samp>
            <span class=sf-dump-index>0</span> => <span class=sf-dump-note>Closure($event, $payload)</span> {<a class=sf-dump-ref>#170</a><samp>
              <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Events\Dispatcher
28 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Dispatcher</span>"
              <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Events\Dispatcher
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Dispatcher</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref235 title="9 occurrences">#35</a>}
              <span class=sf-dump-meta>use</span>: {<samp>
                <span class=sf-dump-meta>$listener</span>: <span class=sf-dump-note>array:2</span> [<samp>
                  <span class=sf-dump-index>0</span> => <span class=sf-dump-note title="Facade\Ignition\LogRecorder\LogRecorder
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Facade\Ignition\LogRecorder</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>LogRecorder</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2106 title="3 occurrences">#106</a>}
                  <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="6 characters">record</span>"
                </samp>]
                <span class=sf-dump-meta>$wildcard</span>: <span class=sf-dump-const>false</span>
              </samp>}
              <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Events\Dispatcher.php
124 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Events\Dispatcher.php</span>"
              <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">345 to 351</span>"
            </samp>}
          </samp>]
          "<span class=sf-dump-key>Illuminate\Foundation\Events\LocaleUpdated</span>" => <span class=sf-dump-note>array:1</span> [<samp>
            <span class=sf-dump-index>0</span> => <span class=sf-dump-note>Closure($event, $payload)</span> {<a class=sf-dump-ref>#184</a><samp>
              <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Events\Dispatcher
28 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Dispatcher</span>"
              <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Events\Dispatcher
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Dispatcher</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref235 title="9 occurrences">#35</a>}
              <span class=sf-dump-meta>use</span>: {<samp>
                <span class=sf-dump-meta>$listener</span>: <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#183</a> &hellip;}
                <span class=sf-dump-meta>$wildcard</span>: <span class=sf-dump-const>false</span>
              </samp>}
              <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Events\Dispatcher.php
124 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Events\Dispatcher.php</span>"
              <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">345 to 351</span>"
            </samp>}
          </samp>]
          "<span class=sf-dump-key>Illuminate\Auth\Events\Registered</span>" => <span class=sf-dump-note>array:1</span> [<samp>
            <span class=sf-dump-index>0</span> => <span class=sf-dump-note>Closure($event, $payload)</span> {<a class=sf-dump-ref>#185</a><samp>
              <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Events\Dispatcher
28 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>Dispatcher</span>"
              <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Events\Dispatcher
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Dispatcher</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref235 title="9 occurrences">#35</a>}
              <span class=sf-dump-meta>use</span>: {<samp>
                <span class=sf-dump-meta>$listener</span>: "<span class=sf-dump-str title="59 characters">Illuminate\Auth\Listeners\SendEmailVerificationNotification</span>"
                <span class=sf-dump-meta>$wildcard</span>: <span class=sf-dump-const>false</span>
              </samp>}
              <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Events\Dispatcher.php
124 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Events\Dispatcher.php</span>"
              <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="10 characters">363 to 371</span>"
            </samp>}
          </samp>]
        </samp>]
        #<span class=sf-dump-protected title="Protected property">wildcards</span>: []
        #<span class=sf-dump-protected title="Protected property">wildcardsCache</span>: <span class=sf-dump-note>array:15</span> [<samp>
          "<span class=sf-dump-key>bootstrapping: Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables</span>" => []
          "<span class=sf-dump-key>bootstrapped: Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables</span>" => []
          "<span class=sf-dump-key>bootstrapping: Illuminate\Foundation\Bootstrap\LoadConfiguration</span>" => []
          "<span class=sf-dump-key>bootstrapped: Illuminate\Foundation\Bootstrap\LoadConfiguration</span>" => []
          "<span class=sf-dump-key>bootstrapping: Illuminate\Foundation\Bootstrap\HandleExceptions</span>" => []
          "<span class=sf-dump-key>bootstrapped: Illuminate\Foundation\Bootstrap\HandleExceptions</span>" => []
          "<span class=sf-dump-key>bootstrapping: Illuminate\Foundation\Bootstrap\RegisterFacades</span>" => []
          "<span class=sf-dump-key>bootstrapped: Illuminate\Foundation\Bootstrap\RegisterFacades</span>" => []
          "<span class=sf-dump-key>bootstrapping: Illuminate\Foundation\Bootstrap\RegisterProviders</span>" => []
          "<span class=sf-dump-key>bootstrapped: Illuminate\Foundation\Bootstrap\RegisterProviders</span>" => []
          "<span class=sf-dump-key>bootstrapping: Illuminate\Foundation\Bootstrap\BootProviders</span>" => []
          "<span class=sf-dump-key>bootstrapped: Illuminate\Foundation\Bootstrap\BootProviders</span>" => []
          "<span class=sf-dump-key>Illuminate\Routing\Events\RouteMatched</span>" => []
          "<span class=sf-dump-key>eloquent.booting: App\Program</span>" => []
          "<span class=sf-dump-key>eloquent.booted: App\Program</span>" => []
        </samp>]
        #<span class=sf-dump-protected title="Protected property">queueResolver</span>: <span class=sf-dump-note>Closure()</span> {<a class=sf-dump-ref>#36</a><samp>
          <span class=sf-dump-meta>class</span>: "<span class=sf-dump-str title="Illuminate\Events\EventServiceProvider
38 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-class">\</span>EventServiceProvider</span>"
          <span class=sf-dump-meta>this</span>: <span class=sf-dump-note title="Illuminate\Events\EventServiceProvider
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Events</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>EventServiceProvider</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref214 title="3 occurrences">#14</a> &hellip;}
          <span class=sf-dump-meta>use</span>: {<samp>
            <span class=sf-dump-meta>$app</span>: <span class=sf-dump-note title="Illuminate\Foundation\Application
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Foundation</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Application</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref22 title="69 occurrences">#2</a>}
          </samp>}
          <span class=sf-dump-meta>file</span>: "<span class=sf-dump-str title="D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor\laravel\framework\src\Illuminate\Events\EventServiceProvider.php
134 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-path">D:\Drive\01 - Proyectos\07 - Grower-lab\Web\App\grower-lab-app\vendor</span><span class="sf-dump-ellipsis sf-dump-ellipsis-path">\laravel\framework\</span>src\Illuminate\Events\EventServiceProvider.php</span>"
          <span class=sf-dump-meta>line</span>: "<span class=sf-dump-str title="8 characters">18 to 20</span>"
        </samp>}
      </samp>}
      #<span class=sf-dump-protected title="Protected property">fetchMode</span>: <span class=sf-dump-num>5</span>
      #<span class=sf-dump-protected title="Protected property">transactions</span>: <span class=sf-dump-num>0</span>
      #<span class=sf-dump-protected title="Protected property">recordsModified</span>: <span class=sf-dump-const>false</span>
      #<span class=sf-dump-protected title="Protected property">queryLog</span>: []
      #<span class=sf-dump-protected title="Protected property">loggingQueries</span>: <span class=sf-dump-const>false</span>
      #<span class=sf-dump-protected title="Protected property">pretending</span>: <span class=sf-dump-const>false</span>
      #<span class=sf-dump-protected title="Protected property">doctrineConnection</span>: <span class=sf-dump-const>null</span>
    </samp>}
    +<span class=sf-dump-public title="Public property">grammar</span>: <span class=sf-dump-note title="Illuminate\Database\Query\Grammars\MySqlGrammar
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database\Query\Grammars</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>MySqlGrammar</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2261 title="2 occurrences">#261</a>}
    +<span class=sf-dump-public title="Public property">processor</span>: <span class=sf-dump-note title="Illuminate\Database\Query\Processors\MySqlProcessor
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">Illuminate\Database\Query\Processors</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>MySqlProcessor</span> {<a class=sf-dump-ref href=#sf-dump-1538836868-ref2262 title="2 occurrences">#262</a>}
    +<span class=sf-dump-public title="Public property">bindings</span>: <span class=sf-dump-note>array:8</span> [<samp>
      "<span class=sf-dump-key>select</span>" => []
      "<span class=sf-dump-key>from</span>" => []
      "<span class=sf-dump-key>join</span>" => []
      "<span class=sf-dump-key>where</span>" => <span class=sf-dump-note>array:1</span> [<samp>
        <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="6 characters">guille</span>"
      </samp>]
      "<span class=sf-dump-key>having</span>" => []
      "<span class=sf-dump-key>order</span>" => []
      "<span class=sf-dump-key>union</span>" => []
      "<span class=sf-dump-key>unionOrder</span>" => []
    </samp>]
    +<span class=sf-dump-public title="Public property">aggregate</span>: <span class=sf-dump-const>null</span>
    +<span class=sf-dump-public title="Public property">columns</span>: <span class=sf-dump-const>null</span>
    +<span class=sf-dump-public title="Public property">distinct</span>: <span class=sf-dump-const>false</span>
    +<span class=sf-dump-public title="Public property">from</span>: "<span class=sf-dump-str title="8 characters">programs</span>"
    +<span class=sf-dump-public title="Public property">joins</span>: <span class=sf-dump-const>null</span>
    +<span class=sf-dump-public title="Public property">wheres</span>: <span class=sf-dump-note>array:1</span> [<samp>
      <span class=sf-dump-index>0</span> => <span class=sf-dump-note>array:5</span> [<samp>
        "<span class=sf-dump-key>type</span>" => "<span class=sf-dump-str title="5 characters">Basic</span>"
        "<span class=sf-dump-key>column</span>" => "<span class=sf-dump-str title="4 characters">name</span>"
        "<span class=sf-dump-key>operator</span>" => "<span class=sf-dump-str>=</span>"
        "<span class=sf-dump-key>value</span>" => "<span class=sf-dump-str title="6 characters">guille</span>"
        "<span class=sf-dump-key>boolean</span>" => "<span class=sf-dump-str title="3 characters">and</span>"
      </samp>]
    </samp>]
    +<span class=sf-dump-public title="Public property">groups</span>: <span class=sf-dump-const>null</span>
    +<span class=sf-dump-public title="Public property">havings</span>: <span class=sf-dump-const>null</span>
    +<span class=sf-dump-public title="Public property">orders</span>: <span class=sf-dump-const>null</span>
    +<span class=sf-dump-public title="Public property">limit</span>: <span class=sf-dump-const>null</span>
    +<span class=sf-dump-public title="Public property">offset</span>: <span class=sf-dump-const>null</span>
    +<span class=sf-dump-public title="Public property">unions</span>: <span class=sf-dump-const>null</span>
    +<span class=sf-dump-public title="Public property">unionLimit</span>: <span class=sf-dump-const>null</span>
    +<span class=sf-dump-public title="Public property">unionOffset</span>: <span class=sf-dump-const>null</span>
    +<span class=sf-dump-public title="Public property">unionOrders</span>: <span class=sf-dump-const>null</span>
    +<span class=sf-dump-public title="Public property">lock</span>: <span class=sf-dump-const>null</span>
    +<span class=sf-dump-public title="Public property">operators</span>: <span class=sf-dump-note>array:30</span> [<samp>
      <span class=sf-dump-index>0</span> => "<span class=sf-dump-str>=</span>"
      <span class=sf-dump-index>1</span> => "<span class=sf-dump-str>&lt;</span>"
      <span class=sf-dump-index>2</span> => "<span class=sf-dump-str>&gt;</span>"
      <span class=sf-dump-index>3</span> => "<span class=sf-dump-str title="2 characters">&lt;=</span>"
      <span class=sf-dump-index>4</span> => "<span class=sf-dump-str title="2 characters">&gt;=</span>"
      <span class=sf-dump-index>5</span> => "<span class=sf-dump-str title="2 characters">&lt;&gt;</span>"
      <span class=sf-dump-index>6</span> => "<span class=sf-dump-str title="2 characters">!=</span>"
      <span class=sf-dump-index>7</span> => "<span class=sf-dump-str title="3 characters">&lt;=&gt;</span>"
      <span class=sf-dump-index>8</span> => "<span class=sf-dump-str title="4 characters">like</span>"
      <span class=sf-dump-index>9</span> => "<span class=sf-dump-str title="11 characters">like binary</span>"
      <span class=sf-dump-index>10</span> => "<span class=sf-dump-str title="8 characters">not like</span>"
      <span class=sf-dump-index>11</span> => "<span class=sf-dump-str title="5 characters">ilike</span>"
      <span class=sf-dump-index>12</span> => "<span class=sf-dump-str>&amp;</span>"
      <span class=sf-dump-index>13</span> => "<span class=sf-dump-str>|</span>"
      <span class=sf-dump-index>14</span> => "<span class=sf-dump-str>^</span>"
      <span class=sf-dump-index>15</span> => "<span class=sf-dump-str title="2 characters">&lt;&lt;</span>"
      <span class=sf-dump-index>16</span> => "<span class=sf-dump-str title="2 characters">&gt;&gt;</span>"
      <span class=sf-dump-index>17</span> => "<span class=sf-dump-str title="5 characters">rlike</span>"
      <span class=sf-dump-index>18</span> => "<span class=sf-dump-str title="9 characters">not rlike</span>"
      <span class=sf-dump-index>19</span> => "<span class=sf-dump-str title="6 characters">regexp</span>"
      <span class=sf-dump-index>20</span> => "<span class=sf-dump-str title="10 characters">not regexp</span>"
      <span class=sf-dump-index>21</span> => "<span class=sf-dump-str>~</span>"
      <span class=sf-dump-index>22</span> => "<span class=sf-dump-str title="2 characters">~*</span>"
      <span class=sf-dump-index>23</span> => "<span class=sf-dump-str title="2 characters">!~</span>"
      <span class=sf-dump-index>24</span> => "<span class=sf-dump-str title="3 characters">!~*</span>"
      <span class=sf-dump-index>25</span> => "<span class=sf-dump-str title="10 characters">similar to</span>"
      <span class=sf-dump-index>26</span> => "<span class=sf-dump-str title="14 characters">not similar to</span>"
      <span class=sf-dump-index>27</span> => "<span class=sf-dump-str title="9 characters">not ilike</span>"
      <span class=sf-dump-index>28</span> => "<span class=sf-dump-str title="3 characters">~~*</span>"
      <span class=sf-dump-index>29</span> => "<span class=sf-dump-str title="4 characters">!~~*</span>"
    </samp>]
    +<span class=sf-dump-public title="Public property">useWritePdo</span>: <span class=sf-dump-const>false</span>
  </samp>}
  #<span class=sf-dump-protected title="Protected property">model</span>: <span class=sf-dump-note title="App\Program
"><span class="sf-dump-ellipsis sf-dump-ellipsis-note">App</span><span class="sf-dump-ellipsis sf-dump-ellipsis-note">\</span>Program</span> {<a class=sf-dump-ref>#238</a><samp>
    +<span class=sf-dump-public title="Public property">guarded</span>: []
    #<span class=sf-dump-protected title="Protected property">connection</span>: <span class=sf-dump-const>null</span>
    #<span class=sf-dump-protected title="Protected property">table</span>: <span class=sf-dump-const>null</span>
    #<span class=sf-dump-protected title="Protected property">primaryKey</span>: "<span class=sf-dump-str title="2 characters">id</span>"
    #<span class=sf-dump-protected title="Protected property">keyType</span>: "<span class=sf-dump-str title="3 characters">int</span>"
    +<span class=sf-dump-public title="Public property">incrementing</span>: <span class=sf-dump-const>true</span>
    #<span class=sf-dump-protected title="Protected property">with</span>: []
    #<span class=sf-dump-protected title="Protected property">withCount</span>: []
    #<span class=sf-dump-protected title="Protected property">perPage</span>: <span class=sf-dump-num>15</span>
    +<span class=sf-dump-public title="Public property">exists</span>: <span class=sf-dump-const>false</span>
    +<span class=sf-dump-public title="Public property">wasRecentlyCreated</span>: <span class=sf-dump-const>false</span>
    #<span class=sf-dump-protected title="Protected property">attributes</span>: []
    #<span class=sf-dump-protected title="Protected property">original</span>: []
    #<span class=sf-dump-protected title="Protected property">changes</span>: []
    #<span class=sf-dump-protected title="Protected property">casts</span>: []
    #<span class=sf-dump-protected title="Protected property">dates</span>: []
    #<span class=sf-dump-protected title="Protected property">dateFormat</span>: <span class=sf-dump-const>null</span>
    #<span class=sf-dump-protected title="Protected property">appends</span>: []
    #<span class=sf-dump-protected title="Protected property">dispatchesEvents</span>: []
    #<span class=sf-dump-protected title="Protected property">observables</span>: []
    #<span class=sf-dump-protected title="Protected property">relations</span>: []
    #<span class=sf-dump-protected title="Protected property">touches</span>: []
    +<span class=sf-dump-public title="Public property">timestamps</span>: <span class=sf-dump-const>true</span>
    #<span class=sf-dump-protected title="Protected property">hidden</span>: []
    #<span class=sf-dump-protected title="Protected property">visible</span>: []
    #<span class=sf-dump-protected title="Protected property">fillable</span>: []
  </samp>}
  #<span class=sf-dump-protected title="Protected property">eagerLoad</span>: []
  #<span class=sf-dump-protected title="Protected property">localMacros</span>: []
  #<span class=sf-dump-protected title="Protected property">onDelete</span>: <span class=sf-dump-const>null</span>
  #<span class=sf-dump-protected title="Protected property">passthru</span>: <span class=sf-dump-note>array:17</span> [<samp>
    <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="6 characters">insert</span>"
    <span class=sf-dump-index>1</span> => "<span class=sf-dump-str title="14 characters">insertOrIgnore</span>"
    <span class=sf-dump-index>2</span> => "<span class=sf-dump-str title="11 characters">insertGetId</span>"
    <span class=sf-dump-index>3</span> => "<span class=sf-dump-str title="11 characters">insertUsing</span>"
    <span class=sf-dump-index>4</span> => "<span class=sf-dump-str title="11 characters">getBindings</span>"
    <span class=sf-dump-index>5</span> => "<span class=sf-dump-str title="5 characters">toSql</span>"
    <span class=sf-dump-index>6</span> => "<span class=sf-dump-str title="4 characters">dump</span>"
    <span class=sf-dump-index>7</span> => "<span class=sf-dump-str title="2 characters">dd</span>"
    <span class=sf-dump-index>8</span> => "<span class=sf-dump-str title="6 characters">exists</span>"
    <span class=sf-dump-index>9</span> => "<span class=sf-dump-str title="11 characters">doesntExist</span>"
    <span class=sf-dump-index>10</span> => "<span class=sf-dump-str title="5 characters">count</span>"
    <span class=sf-dump-index>11</span> => "<span class=sf-dump-str title="3 characters">min</span>"
    <span class=sf-dump-index>12</span> => "<span class=sf-dump-str title="3 characters">max</span>"
    <span class=sf-dump-index>13</span> => "<span class=sf-dump-str title="3 characters">avg</span>"
    <span class=sf-dump-index>14</span> => "<span class=sf-dump-str title="7 characters">average</span>"
    <span class=sf-dump-index>15</span> => "<span class=sf-dump-str title="3 characters">sum</span>"
    <span class=sf-dump-index>16</span> => "<span class=sf-dump-str title="13 characters">getConnection</span>"
  </samp>]
  #<span class=sf-dump-protected title="Protected property">scopes</span>: []
  #<span class=sf-dump-protected title="Protected property">removedScopes</span>: []
</samp>}
</pre><script>Sfdump("sf-dump-1538836868")</script>
