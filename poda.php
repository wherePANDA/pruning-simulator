<?php /* poda.php - Simulador de poda */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Simulador de Poda – Formación y Mantenimiento</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <meta name="color-scheme" content="light only">
  <style>
    /* Fondo sutil cuadriculado para el canvas */
    .grid-bg {
      background-image:
        linear-gradient(to right, rgba(17,24,39,.06) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(17,24,39,.06) 1px, transparent 1px);
      background-size: 24px 24px;
    }
    .segmented > button {
      @apply px-3 py-2 text-sm rounded-xl border transition;
    }
    .segmented > button.is-active {
      @apply bg-gray-900 text-white shadow-sm border-gray-900;
    }
    .segmented > button:not(.is-active) {
      @apply bg-white hover:bg-gray-100 text-gray-800 border-gray-200;
    }
    .metric-bar { height: 8px; }
  </style>
</head>
<body class="min-h-screen bg-white text-gray-900">
  <!-- Header -->
  <header class="relative overflow-hidden">
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-emerald-200/60 via-teal-200/50 to-cyan-200/60"></div>
    <div class="relative max-w-7xl mx-auto px-4 md:px-8 py-8">
      <h1 class="text-3xl md:text-4xl font-black tracking-tight text-gray-900">
        Simulador de Poda
      </h1>
      <p class="mt-2 text-sm md:text-base text-gray-700 max-w-2xl">
        Marca con un clic las <b>ramas a cortar</b>. Cambia la <b>vista</b> y el <b>tipo de poda</b>. Pulsa <b>Evaluar</b> para obtener sugerencias didácticas.
      </p>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 md:px-8 py-6">
    <section class="grid lg:grid-cols-3 gap-6 items-start">
      <!-- Panel de control -->
      <aside class="space-y-5 lg:sticky lg:top-6">
        <!-- Selecciones -->
        <div class="rounded-2xl border border-gray-200 bg-white/80 backdrop-blur p-5 shadow-sm">
          <h2 class="font-semibold text-sm mb-2 text-gray-900">Tipo de planta</h2>
          <div id="plant-buttons" class="segmented flex flex-wrap gap-2"></div>

          <h2 class="font-semibold text-sm mt-4 mb-2 text-gray-900">Vista</h2>
          <div id="view-buttons" class="segmented flex flex-wrap gap-2"></div>

          <h2 class="font-semibold text-sm mt-4 mb-2 text-gray-900">Tipo de poda</h2>
          <div id="mode-buttons" class="segmented flex flex-wrap gap-2"></div>

          <div class="grid grid-cols-3 gap-2 pt-4">
            <button id="btn-clear" class="px-3 py-2 rounded-xl border border-gray-200 hover:bg-gray-100 text-sm">Limpiar</button>
            <button id="btn-eval"  class="px-3 py-2 rounded-xl bg-gray-900 text-white text-sm shadow-sm">Evaluar</button>
            <button id="btn-reset" class="px-3 py-2 rounded-xl border border-gray-200 hover:bg-gray-100 text-sm">Reiniciar</button>
          </div>
        </div>

        <!-- Reglas -->
        <div class="rounded-2xl border border-gray-200 bg-white/80 backdrop-blur p-5 shadow-sm">
          <p class="text-sm font-semibold mb-1 text-gray-900">
            Reglas didácticas <span id="rules-mode" class="font-normal text-gray-600"></span>
          </p>
          <ul id="rules-list" class="list-disc ml-5 space-y-1 text-sm text-gray-700"></ul>
        </div>

        <!-- Estado + métricas -->
        <div class="rounded-2xl border border-gray-200 bg-white/80 backdrop-blur p-5 shadow-sm space-y-3">
          <p class="text-sm font-semibold text-gray-900">Estado</p>
          <div class="grid grid-cols-3 gap-3 text-center">
            <div class="rounded-xl border border-gray-100 p-3">
              <p class="text-[11px] uppercase tracking-wide text-gray-500">Cortes</p>
              <p class="text-xl font-bold text-gray-900"><span id="state-cuts">0</span></p>
            </div>
            <div class="rounded-xl border border-gray-100 p-3">
              <p class="text-[11px] uppercase tracking-wide text-gray-500">% masa</p>
              <p class="text-xl font-bold text-gray-900"><span id="state-removed">0%</span></p>
              <div class="mt-1 w-full bg-gray-200 rounded-full metric-bar overflow-hidden">
                <div id="bar-removed" class="h-full bg-emerald-500" style="width:0%"></div>
              </div>
            </div>
            <div class="rounded-xl border border-gray-100 p-3">
              <p class="text-[11px] uppercase tracking-wide text-gray-500">Puntuación</p>
              <p class="text-xl font-bold text-gray-900"><span id="state-score">100/100</span></p>
              <div class="mt-1 w-full bg-gray-200 rounded-full metric-bar overflow-hidden">
                <div id="bar-score" class="h-full bg-cyan-500" style="width:100%"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sugerencias -->
        <div id="suggestions-card" class="hidden rounded-2xl border border-gray-200 bg-white/80 backdrop-blur p-5 shadow-sm">
          <p class="text-sm font-semibold mb-2 text-gray-900">Sugerencias</p>
          <ul id="suggestions-list" class="space-y-2 text-sm text-gray-700"></ul>
        </div>
      </aside>

      <!-- Lienzo -->
      <section class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="grid-bg rounded-t-2xl p-2 md:p-4">
          <svg id="canvas" class="w-full h-[560px] bg-white rounded-xl block" viewBox="0 0 800 520" role="img" aria-label="Lienzo de planta para simular poda"></svg>
        </div>
        <div class="px-4 md:px-6 py-3 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
          <p id="tip" class="text-xs md:text-sm text-gray-700">
            Consejo: orienta tus cortes hacia <b>yemas externas</b> para abrir la copa y mejorar luz/ventilación.
            En vista aérea, busca <b>distribución radial</b> de las ramas principales.
          </p>
        </div>
      </section>
    </section>
  </main>

  <script>
    // ------------------------------ Estado ------------------------------
    const state = {
      plantType: "Árbol joven",           // "Arbusto joven" | "Rosal" | "Árbol joven"
      viewMode: "Lateral",                // "Lateral" | "Aérea"
      mode: "Formación",                  // "Formación" | "Mantenimiento"
      original: [],                       // nodos
      cuts: new Set(),                    // ids de nodo (segmento) a cortar
      pruned: [],                         // nodos tras corte
      eval: { removedPct: 0, score: 100, messages: [] }
    };

    // ------------------------------ Utilidades ------------------------------
    const $ = (id) => document.getElementById(id);
    function elNS(tag){ return document.createElementNS("http://www.w3.org/2000/svg", tag); }
    function cloneNodes(arr){ return arr.map(n => ({...n, p: {...n.p}, buds: (n.buds||[]).map(b => ({...b})) })); }

    function computeSubtreeWeights(nodes){
      const byId = Object.fromEntries(nodes.map(n => [n.id, n]));
      const children = new Map();
      nodes.forEach(n => {
        if(n.parentId !== null){
          if(!children.has(n.parentId)) children.set(n.parentId, []);
          children.get(n.parentId).push(n.id);
        }
      });
      const weight = (id) => {
        const ch = children.get(id) || [];
        const w = ch.reduce((acc, cid) => acc + weight(cid), 1);
        byId[id].weight = w;
        return w;
      };
      nodes.forEach(n => n.weight = 1);
      nodes.filter(n => n.parentId === null).forEach(n => weight(n.id));
      return nodes;
    }

    function prune(nodes, cutIds){
      const toRemove = new Set();
      const byParent = new Map();
      nodes.forEach(n => {
        if(!byParent.has(n.parentId)) byParent.set(n.parentId, []);
        byParent.get(n.parentId).push(n.id);
      });
      const mark = (id) => { toRemove.add(id); (byParent.get(id)||[]).forEach(mark); };
      cutIds.forEach(mark);
      return nodes.filter(n => !toRemove.has(n.id));
    }

    // ------------------------------ Modelos de planta ------------------------------
    function makeBush(){
      const base = { x: 400, y: 460 };
      let id = 0;
      const nodes = [];
      const add = (parentId, p, kind="branch") => {
        const node = { id: id++, parentId, p, kind, buds: [], weight: 1 };
        nodes.push(node); return node.id;
      };
      const trunk = add(null, { x: base.x, y: base.y }, "trunk");
      const tips = [
        { x: base.x - 140, y: base.y - 180 },
        { x: base.x - 30,  y: base.y - 220 },
        { x: base.x + 80,  y: base.y - 210 },
        { x: base.x + 160, y: base.y - 160 },
      ];
      const layer1 = tips.map(t => add(trunk, t));
      layer1.forEach((pid, i) => {
        const parent = nodes.find(n => n.id === pid);
        const dir = i % 2 === 0 ? -1 : 1;
        const a = add(pid, { x: parent.p.x + dir*50, y: parent.p.y - 60 });
        const b = add(pid, { x: parent.p.x - dir*40, y: parent.p.y - 40 });
        [pid, a, b].forEach((nid, j) => {
          const node = nodes.find(n => n.id === nid);
          node.buds = [
            { x: node.p.x + (j===0?0:6), y: node.p.y - (j===0?6:8), dir: "out" },
            { x: node.p.x - (j===0?0:6), y: node.p.y - (j===0?6:8), dir: "in" },
          ];
        });
      });
      return nodes;
    }

    function makeRose(){
      const base = { x: 400, y: 470 };
      let id = 0;
      const nodes = [];
      const add = (parentId, p, kind="branch") => {
        const node = { id: id++, parentId, p, kind, buds: [], weight: 1 };
        nodes.push(node); return node.id;
      };
      const crown = [
        { x: base.x - 130, y: base.y - 220 },
        { x: base.x - 20,  y: base.y - 250 },
        { x: base.x + 100, y: base.y - 230 },
        { x: base.x + 160, y: base.y - 180 },
      ].map(p => add(null, p));
      crown.forEach(pid => {
        const parent = nodes.find(n => n.id === pid);
        const a = add(pid, { x: parent.p.x + 40, y: parent.p.y - 60 });
        const b = add(pid, { x: parent.p.x - 30, y: parent.p.y - 50 });
        [pid, a, b].forEach(nid => {
          const node = nodes.find(n => n.id === nid);
          node.buds = [
            { x: node.p.x + 6, y: node.p.y - 8, dir: "out" },
            { x: node.p.x - 6, y: node.p.y - 8, dir: "in" },
          ];
        });
      });
      return nodes;
    }

    function makeYoungTree(){
      const base = { x: 400, y: 480 };
      let id = 0;
      const nodes = [];
      const add = (parentId, p, kind="branch") => {
        const node = { id: id++, parentId, p, kind, buds: [], weight: 1 };
        nodes.push(node); return node.id;
      };
      const trunk = add(null, { x: base.x, y: base.y }, "trunk");
      const levels = [
        { x: base.x - 120, y: base.y - 140 },
        { x: base.x + 120, y: base.y - 200 },
        { x: base.x - 100, y: base.y - 260 },
        { x: base.x + 90,  y: base.y - 320 },
      ];
      const tip = add(trunk, { x: base.x, y: base.y - 360 }, "trunk");
      const laterals = levels.map(p => add(trunk, p));
      laterals.forEach((pid, i) => {
        const parent = nodes.find(n => n.id === pid);
        const a = add(pid, { x: parent.p.x + (i%2?40:-40), y: parent.p.y - 60 });
        [pid, a].forEach(nid => {
          const node = nodes.find(n => n.id === nid);
          node.buds = [
            { x: node.p.x + 6, y: node.p.y - 8, dir: "out" },
            { x: node.p.x - 6, y: node.p.y - 8, dir: "in" },
          ];
        });
      });
      nodes.find(n => n.id === tip).buds = [{ x: base.x + 4, y: base.y - 370, dir: "out" }];
      return nodes;
    }

    const PLANTS = {
      "Arbusto joven": makeBush,
      "Rosal": makeRose,
      "Árbol joven": makeYoungTree
    };

    // ------------------------------ Evaluación ------------------------------
    function evaluatePruning(original, pruned, cutIds, plantType, mode){
      const origW = computeSubtreeWeights(cloneNodes(original));
      const pruW  = computeSubtreeWeights(cloneNodes(pruned));
      const totalOrig = origW.reduce((a, n) => a + n.weight, 0);
      const totalPruned = pruW.reduce((a, n) => a + n.weight, 0);
      const removed = totalOrig - totalPruned;
      const removedPct = Math.round((removed / totalOrig) * 100);

      const messages = [];
      let score = 100;

      const limit = (mode === "Formación") ? 30 : 20;
      if (removedPct > limit){
        messages.push(`Has eliminado más del ${limit}% de la masa para poda de ${mode}. Reduce los cortes.`);
        score -= (removedPct - limit);
      } else {
        messages.push(`Cantidad de corte adecuada (≤${limit}%).`);
      }

      if (plantType === "Árbol joven" && mode === "Formación"){
        const hadTrunk = original.some(n => n.kind === "trunk");
        const stillTrunk = pruned.some(n => n.kind === "trunk");
        if (!stillTrunk && hadTrunk){
          messages.push("Has eliminado el eje principal: en formación conviene conservarlo.");
          score -= 30;
        } else {
          messages.push("Eje principal conservado: bien para conducción.");
        }
      }

      const cx = 400;
      const leftB  = original.filter(n => n.p.x < cx).length;
      const rightB = original.filter(n => n.p.x >= cx).length;
      const leftA  = pruned.filter(n => n.p.x < cx).length;
      const rightA = pruned.filter(n => n.p.x >= cx).length;
      const balB = Math.abs(leftB - rightB);
      const balA = Math.abs(leftA - rightA);
      if (balA > balB + 2){
        messages.push("La copa quedó desequilibrada. Compensa los lados o abre radialmente.");
        score -= 12;
      } else {
        messages.push("Buen equilibrio general.");
      }

      let outCuts = 0;
      cutIds.forEach(id => {
        const node = original.find(n => n.id === id);
        if (node && (node.buds||[]).some(b => b.dir === "out")) outCuts += 1;
      });
      if (mode === "Formación"){
        if (outCuts >= Math.ceil(cutIds.size / 2)){
          messages.push("Has orientado la mayoría de cortes sobre yemas externas: favorece apertura y luz.");
        } else {
          messages.push("Procura elegir yemas externas para abrir la copa.");
          score -= 10;
        }
      } else {
        messages.push("Mantenimiento: prioriza eliminar seco/dañado y cruces; cortes sobre yemas externas mejoran ventilación.");
      }

      score = Math.max(0, Math.min(100, score));
      return { removedPct, score, messages };
    }

    // ------------------------------ UI helpers ------------------------------
    const plantButtons = $("plant-buttons");
    const viewButtons  = $("view-buttons");
    const modeButtons  = $("mode-buttons");
    const svg          = $("canvas");

    const lblCuts    = $("state-cuts");
    const lblRemoved = $("state-removed");
    const lblScore   = $("state-score");
    const barRemoved = $("bar-removed");
    const barScore   = $("bar-score");

    const suggCard  = $("suggestions-card");
    const suggList  = $("suggestions-list");
    const rulesMode = $("rules-mode");
    const rulesList = $("rules-list");

    function renderButtons(){
      // Plantas
      plantButtons.innerHTML = "";
      Object.keys(PLANTS).forEach(k => {
        const btn = document.createElement("button");
        btn.className = "segmented-btn";
        btn.textContent = k;
        btn.classList.add(...("px-3 py-2 rounded-xl border text-sm transition".split(" ")));
        if (state.plantType === k) btn.classList.add("is-active"); else btn.classList.add("bg-white","hover:bg-gray-100","border-gray-200");
        btn.onclick = () => { state.plantType = k; resetPlant(); refreshButtons(); };
        plantButtons.appendChild(btn);
      });

      // Vistas
      viewButtons.innerHTML = "";
      ["Lateral","Aérea"].forEach(v => {
        const btn = document.createElement("button");
        btn.textContent = v;
        btn.classList.add(...("px-3 py-2 rounded-xl border text-sm transition".split(" ")));
        if (state.viewMode === v) btn.classList.add("is-active"); else btn.classList.add("bg-white","hover:bg-gray-100","border-gray-200");
        btn.onclick = () => { state.viewMode = v; refreshButtons(); draw(); };
        viewButtons.appendChild(btn);
      });

      // Modos
      modeButtons.innerHTML = "";
      ["Formación","Mantenimiento"].forEach(m => {
        const btn = document.createElement("button");
        btn.textContent = m;
        btn.classList.add(...("px-3 py-2 rounded-xl border text-sm transition".split(" ")));
        if (state.mode === m) btn.classList.add("is-active"); else btn.classList.add("bg-white","hover:bg-gray-100","border-gray-200");
        btn.onclick = () => { state.mode = m; renderRules(); evaluateAndUpdate(); refreshButtons(); };
        modeButtons.appendChild(btn);
      });
    }
    function refreshButtons(){
      document.querySelectorAll("#plant-buttons button").forEach(b => b.classList.toggle("is-active", b.textContent===state.plantType));
      document.querySelectorAll("#view-buttons button").forEach(b => b.classList.toggle("is-active", b.textContent===state.viewMode));
      document.querySelectorAll("#mode-buttons button").forEach(b => b.classList.toggle("is-active", b.textContent===state.mode));
    }

    function renderRules(){
      rulesMode.textContent = `(${state.mode})`;
      rulesList.innerHTML = "";
      const rules = (state.mode === "Formación")
        ? [
            "Quitar ≤ 30% de la masa vegetal.",
            "Mantener el eje principal (árbol joven).",
            "Favorecer yema externa para abrir copa.",
            "Equilibrio lateral o distribución radial (vista aérea)."
          ]
        : [
            "Quitar ≤ 20% de la masa vegetal.",
            "Priorizar 3D: dañado, seco, enfermo.",
            "Eliminar cruces y ramas hacia dentro.",
            "Respetar forma y tamaño actuales."
          ];
      rules.forEach(t => {
        const li = document.createElement("li");
        li.textContent = t;
        rulesList.appendChild(li);
      });
    }

    // Proyección aérea
    function projectTopDown(pt){
      const cx = 400, cy = 480;
      const r = Math.max(0, cy - pt.y);
      const theta = ((pt.x - cx) / 220) * Math.PI * 0.9;
      return { x: cx + r * Math.cos(theta), y: cy - r * Math.sin(theta) };
    }
    function mapPoint(pt){
      return (state.viewMode === "Aérea") ? projectTopDown(pt) : pt;
    }

    function buildSegments(nodes){
      const byId = Object.fromEntries(nodes.map(n => [n.id, n]));
      const segs = [];
      nodes.forEach(n => {
        if(n.parentId !== null && byId[n.parentId]){
          segs.push({ id: n.id, from: byId[n.parentId].p, to: n.p, buds: (byId[n.id]?.buds||[]) });
        }
      });
      return segs;
    }

    // Dibujo
    function draw(){
      svg.innerHTML = "";

      // Suelo / disco
      if (state.viewMode === "Lateral"){
        const rect = elNS("rect");
        rect.setAttribute("x","0"); rect.setAttribute("y","500");
        rect.setAttribute("width","800"); rect.setAttribute("height","20");
        rect.setAttribute("fill","#e5e7eb");
        svg.appendChild(rect);
      } else {
        const g = elNS("g");
        const c = elNS("circle");
        c.setAttribute("cx","400"); c.setAttribute("cy","480"); c.setAttribute("r","80");
        c.setAttribute("fill","#f3f4f6"); c.setAttribute("stroke","#e5e7eb");
        const t = elNS("text");
        t.setAttribute("x","400"); t.setAttribute("y","480"); t.setAttribute("font-size","10");
        t.setAttribute("fill","#6b7280"); t.setAttribute("text-anchor","middle"); t.setAttribute("dominant-baseline","middle");
        t.textContent = "tronco";
        g.appendChild(c); g.appendChild(t); svg.appendChild(g);
      }

      // Ramas
      const segs = buildSegments(state.original);
      segs.forEach(s => {
        const from = mapPoint(s.from);
        const to   = mapPoint(s.to);

        const g = elNS("g");

        const line = elNS("line");
        line.setAttribute("x1", from.x); line.setAttribute("y1", from.y);
        line.setAttribute("x2", to.x);   line.setAttribute("y2", to.y);
        line.setAttribute("stroke", state.cuts.has(s.id) ? "#ef4444" : "#334155");
        line.setAttribute("stroke-width", state.cuts.has(s.id) ? "4" : "3");
        line.setAttribute("stroke-linecap","round");
        line.style.cursor = "pointer";
        line.addEventListener("click", () => toggleCut(s.id));
        g.appendChild(line);

        (s.buds || []).forEach(b => {
          const bp = mapPoint(b);
          const circ = elNS("circle");
          circ.setAttribute("cx", bp.x); circ.setAttribute("cy", bp.y); circ.setAttribute("r","4");
          circ.setAttribute("fill", b.dir === "out" ? "#10b981" : "#9ca3af");
          g.appendChild(circ);
        });

        svg.appendChild(g);
      });

      // Base/tronco
      state.original.filter(n => n.parentId === null).forEach(n => {
        const p = mapPoint(n.p);
        const base = elNS("circle");
        base.setAttribute("cx", p.x); base.setAttribute("cy", p.y); base.setAttribute("r","6");
        base.setAttribute("fill","#111827");
        svg.appendChild(base);
      });

      // Leyenda
      const legend = elNS("g");
      legend.setAttribute("transform","translate(16,16)");
      const box = elNS("rect");
      box.setAttribute("x","-10"); box.setAttribute("y","-10");
      box.setAttribute("width","320"); box.setAttribute("height","110");
      box.setAttribute("rx","12"); box.setAttribute("fill","#f9fafb"); box.setAttribute("stroke","#e5e7eb");
      legend.appendChild(box);

      const legendItems = [
        { shape:"circle", cx:8, cy:8, r:4, fill:"#10b981", text:"Yema externa (preferible)", ty:12 },
        { shape:"circle", cx:8, cy:30, r:4, fill:"#9ca3af", text:"Yema interna", ty:34 },
        { shape:"line", x1:2, y1:52, x2:18, y2:52, stroke:"#334155", w:3, text:"Rama (click para marcar corte)", ty:56 },
        { shape:"line", x1:2, y1:74, x2:18, y2:74, stroke:"#ef4444", w:4, text:"Rama marcada para cortar", ty:78 },
      ];
      legendItems.forEach(it => {
        if(it.shape==="circle"){
          const c = elNS("circle");
          c.setAttribute("cx", it.cx); c.setAttribute("cy", it.cy); c.setAttribute("r", it.r);
          c.setAttribute("fill", it.fill);
          legend.appendChild(c);
        } else {
          const l = elNS("line");
          l.setAttribute("x1", it.x1); l.setAttribute("y1", it.y1); l.setAttribute("x2", it.x2); l.setAttribute("y2", it.y2);
          l.setAttribute("stroke", it.stroke); l.setAttribute("stroke-width", it.w);
          legend.appendChild(l);
        }
        const tt = elNS("text");
        tt.setAttribute("x","22"); tt.setAttribute("y", it.ty);
        tt.setAttribute("font-size","12"); tt.setAttribute("fill","#111827");
        tt.textContent = it.text;
        legend.appendChild(tt);
      });
      const tv = elNS("text");
      tv.setAttribute("x","22"); tv.setAttribute("y","100"); tv.setAttribute("font-size","11"); tv.setAttribute("fill","#111827");
      tv.textContent = `Vista: ${state.viewMode} (cámbiala en el panel)`;
      legend.appendChild(tv);
      svg.appendChild(legend);

      // Estado
      updateStatePanel();
    }

    // Interacciones
    function toggleCut(id){
      if(state.cuts.has(id)) state.cuts.delete(id); else state.cuts.add(id);
      evaluateAndUpdate();
      draw();
    }

    function evaluateAndUpdate(){
      const pruned = prune(state.original, state.cuts);
      state.pruned = pruned;
      state.eval = evaluatePruning(state.original, pruned, state.cuts, state.plantType, state.mode);
      updateStatePanel();
    }

    function updateStatePanel(){
      lblCuts.textContent = state.cuts.size;
      lblRemoved.textContent = `${state.eval.removedPct}%`;
      lblScore.textContent = `${state.eval.score}/100`;
      barRemoved.style.width = `${Math.min(100, state.eval.removedPct)}%`;
      barScore.style.width = `${state.eval.score}%`;
    }

    function resetPlant(){
      state.cuts = new Set();
      $("suggestions-card").classList.add("hidden");
      state.original = computeSubtreeWeights(PLANTS[state.plantType]());
      state.pruned = cloneNodes(state.original);
      state.eval = { removedPct: 0, score: 100, messages: [] };
      evaluateAndUpdate();
      draw();
    }

    // Sugerencias
    $("btn-clear").addEventListener("click", () => { state.cuts = new Set(); evaluateAndUpdate(); draw(); });
    $("btn-eval").addEventListener("click", () => {
      suggList.innerHTML = "";
      state.eval.messages.forEach(m => {
        const li = document.createElement("li");
        li.className = "flex items-start gap-2";
        li.innerHTML = `<span class="inline-block w-1.5 h-1.5 mt-1 rounded-full bg-emerald-500"></span><span>${m}</span>`;
        suggList.appendChild(li);
      });
      suggCard.classList.remove("hidden");
      suggCard.scrollIntoView({behavior: "smooth", block: "nearest"});
    });
    $("btn-reset").addEventListener("click", resetPlant);

    // Init
    function init(){
      // Botoneras y reglas
      renderButtons();
      renderRules();
      resetPlant();
    }
    init();
  </script>
</body>
</html>