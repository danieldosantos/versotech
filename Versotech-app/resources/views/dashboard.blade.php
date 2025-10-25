<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processamento de Produtos e Preços</title>
    <style>
        :root {
            color-scheme: light dark;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 2rem;
            background-color: #f5f5f5;
            color: #1f2933;
        }
        h1 {
            margin-bottom: 1rem;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.1);
            padding: 2rem;
            max-width: min(98vw, 1920px);
            margin: 0 auto;
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        button {
            background: #2563eb;
            border: none;
            border-radius: 999px;
            color: #fff;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            padding: 0.75rem 1.75rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(37, 99, 235, 0.25);
            background: #1d4ed8;
        }
        button:disabled {
            cursor: not-allowed;
            background: #9ca3af;
            box-shadow: none;
            transform: none;
        }
        .status {
            min-height: 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        .table-wrapper {
            width: 100%;
            overflow-x: auto; /* permite rolagem horizontal estilo planilha */
            -webkit-overflow-scrolling: touch;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }
        th, td {
            padding: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        th {
            white-space: nowrap;
        }
        td {
            white-space: normal;
            overflow: visible;
            text-overflow: clip;
            word-break: break-word;
        }
        thead {
            background: #1f2937;
            color: #fff;
        }

        /* Modo somente produtos: sem scroll horizontal, com quebras controladas */
        #products-table.product-only { table-layout: fixed; }
        #products-table.product-only th {
            white-space: nowrap;   /* cabeçalhos inteiros, sem quebra */
            line-height: 1.15;
            font-size: 0.9rem;
        }
        #products-table.product-only td {
            white-space: normal;   /* permite visualizar textos longos */
            padding: 0.5rem 0.6rem; /* compensa a largura reduzida */
            font-size: 0.9rem;
            word-break: break-word;
        }
        /* Larguras fixas por coluna (somente no modo produto) */
        #products-table.product-only th:nth-child(1), #products-table.product-only td:nth-child(1) { width: 70px; }
        #products-table.product-only th:nth-child(2), #products-table.product-only td:nth-child(2) { width: 200px; }
        #products-table.product-only th:nth-child(3), #products-table.product-only td:nth-child(3) { width: 150px; }
        #products-table.product-only th:nth-child(4), #products-table.product-only td:nth-child(4) { width: 130px; }
        #products-table.product-only th:nth-child(5), #products-table.product-only td:nth-child(5) { width: 110px; }
        #products-table.product-only th:nth-child(6), #products-table.product-only td:nth-child(6) { width: 90px; }
        #products-table.product-only th:nth-child(7), #products-table.product-only td:nth-child(7) { width: 90px; text-align: right; }
        #products-table.product-only th:nth-child(8), #products-table.product-only td:nth-child(8) { width: 150px; }
        #products-table.product-only th:nth-child(9), #products-table.product-only td:nth-child(9) { width: 120px; }
        #products-table.product-only th:nth-child(10), #products-table.product-only td:nth-child(10) { width: 120px; }
        #products-table.product-only th:nth-child(11), #products-table.product-only td:nth-child(11) { width: 110px; }
        #products-table.product-only th:nth-child(12), #products-table.product-only td:nth-child(12) { width: 130px; }
        #products-table.product-only th:nth-child(13), #products-table.product-only td:nth-child(13) { width: 200px; }
        #products-table.product-only th:nth-child(14), #products-table.product-only td:nth-child(14) { width: 90px; }

        /* Modo com preços: também sem scroll, usando layout fixo e quebra de linha */
        #products-table.price-full { table-layout: fixed; }
        #products-table.price-full th {
            white-space: nowrap;    /* cabeçalhos inteiros */
            line-height: 1.15;
            font-size: 0.9rem;
            padding: 0.55rem 0.6rem;
        }
        #products-table.price-full td {
            font-size: 0.95rem;
            padding: 0.6rem 0.65rem;
            white-space: normal;    /* permite visualizar textos longos */
            line-height: 1.25;
            word-break: break-word;
        }
        /* alinhar colunas numéricas à direita no modo com preços */
        #products-table.price-full td:nth-child(7),  /* Peso */
        #products-table.price-full td:nth-child(10), /* Valor */
        #products-table.price-full td:nth-child(11), /* Promoção */
        #products-table.price-full td:nth-child(12), /* Desconto */
        #products-table.price-full td:nth-child(13), /* Acréscimo */
        #products-table.price-full td:nth-child(22), /* Preço Efetivo */
        #products-table.price-full td:nth-child(23)  /* Tem Preço */
        { text-align: right; }

        /* Larguras fixas por coluna (modo com preços) */
        #products-table.price-full th:nth-child(1),  #products-table.price-full td:nth-child(1)  { width: 70px; }
        #products-table.price-full th:nth-child(2),  #products-table.price-full td:nth-child(2)  { width: 180px; }
        #products-table.price-full th:nth-child(3),  #products-table.price-full td:nth-child(3)  { width: 140px; }
        #products-table.price-full th:nth-child(4),  #products-table.price-full td:nth-child(4)  { width: 130px; }
        #products-table.price-full th:nth-child(5),  #products-table.price-full td:nth-child(5)  { width: 110px; }
        #products-table.price-full th:nth-child(6),  #products-table.price-full td:nth-child(6)  { width: 90px; }
        #products-table.price-full th:nth-child(7),  #products-table.price-full td:nth-child(7)  { width: 90px; }
        #products-table.price-full th:nth-child(8),  #products-table.price-full td:nth-child(8)  { width: 150px; }
        #products-table.price-full th:nth-child(9),  #products-table.price-full td:nth-child(9)  { width: 70px; }
        #products-table.price-full th:nth-child(10), #products-table.price-full td:nth-child(10) { width: 100px; }
        #products-table.price-full th:nth-child(11), #products-table.price-full td:nth-child(11) { width: 100px; }
        #products-table.price-full th:nth-child(12), #products-table.price-full td:nth-child(12) { width: 95px; }
        #products-table.price-full th:nth-child(13), #products-table.price-full td:nth-child(13) { width: 95px; }
        #products-table.price-full th:nth-child(14), #products-table.price-full td:nth-child(14) { width: 120px; }
        #products-table.price-full th:nth-child(15), #products-table.price-full td:nth-child(15) { width: 120px; }
        #products-table.price-full th:nth-child(16), #products-table.price-full td:nth-child(16) { width: 120px; }
        #products-table.price-full th:nth-child(17), #products-table.price-full td:nth-child(17) { width: 120px; }
        #products-table.price-full th:nth-child(18), #products-table.price-full td:nth-child(18) { width: 110px; }
        #products-table.price-full th:nth-child(19), #products-table.price-full td:nth-child(19) { width: 120px; }
        #products-table.price-full th:nth-child(20), #products-table.price-full td:nth-child(20) { width: 200px; }
        #products-table.price-full th:nth-child(21), #products-table.price-full td:nth-child(21) { width: 90px; }
        #products-table.price-full th:nth-child(22), #products-table.price-full td:nth-child(22) { width: 110px; }
        #products-table.price-full th:nth-child(23), #products-table.price-full td:nth-child(23) { width: 90px; }

        /* --- Card mode (sem scroll, todas informações visíveis) --- */
        #products-table.cards { width: 100%; }
        #products-table.cards thead { display: none; }
        #products-table.cards tbody { display: block; }
        #products-table.cards tbody tr {
            display: grid;
            grid-template-columns: repeat(4, minmax(180px, 1fr));
            gap: 0.5rem 1.5rem;
            background: #f9fafb;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        #products-table.cards td {
            border: none;
            white-space: normal;       /* mostrar conteúdo por completo */
            overflow: visible;
            text-overflow: clip;
            line-height: 1.3;
        }
        #products-table.cards td::before {
            content: attr(data-label);
            display: block;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 0.25rem;
        }
        /* Ajuste responsivo do grid */
        @media (max-width: 1200px) {
            #products-table.cards tbody tr { grid-template-columns: repeat(3, minmax(180px, 1fr)); }
        }
        @media (max-width: 900px) {
            #products-table.cards tbody tr { grid-template-columns: repeat(2, minmax(160px, 1fr)); }
        }
        .empty {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }
        @media (max-width: 900px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                display: none;
            }
            tbody tr {
                margin-bottom: 1.5rem;
                background: #f9fafb;
                border-radius: 8px;
                padding: 1rem;
            }
            tbody td {
                border: none;
                position: relative;
                padding-left: 50%;
                white-space: normal; /* em mobile pode quebrar para caber melhor */
            }
            tbody td::before {
                position: absolute;
                left: 1rem;
                width: 45%;
                white-space: nowrap;
                font-weight: 600;
                color: #4b5563;
                content: attr(data-label);
            }
        }
    </style>
</head>
<body>
<div class="card">
    <h1>Transformação de Produtos e Preços</h1>
    <p>Utilize os botões abaixo para processar as views SQL e carregar os dados tratados nas tabelas de destino. Em seguida, visualize os produtos com seus respectivos preços.</p>

    <div class="actions">
        <button id="process-products">Processar Produtos</button>
        <button id="process-prices">Processar Preços</button>
        <button id="refresh-list">Listar Produtos com Preços</button>
    </div>

    <div class="status" id="status"></div>

    <div class="table-wrapper">
        <table id="products-table">
            <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Fabricante</th>
                <th>Modelo</th>
                <th>Cor</th>
                <th>Peso (kg)</th>
                <th>Dimensões (L×A×P cm)</th>
                <th>Moeda</th>
                <th>Valor</th>
                <th>Promoção</th>
                <th>Desconto (%)</th>
                <th>Acréscimo (%)</th>
                <th>Início Promoção</th>
                <th>Fim Promoção</th>
                <th>Atualização</th>
                <th>Origem</th>
                <th>Tipo Cliente</th>
                <th>Vendedor</th>
                <th>Observações</th>
                <th>Status</th>
                <th>Preço Efetivo</th>
                <th>Tem Preço</th>
            </tr>
            </thead>
            <tbody id="products-body">
            <tr class="empty"><td colspan="23">Nenhum produto processado ainda.</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    const statusElement = document.getElementById('status');
    const processProductsButton = document.getElementById('process-products');
    const processPricesButton = document.getElementById('process-prices');
    const refreshButton = document.getElementById('refresh-list');
    const tableBody = document.getElementById('products-body');
    const tableHead = document.querySelector('#products-table thead');

    // Tabela tradicional completa (todas as colunas)
    const FULL_TABLE_HEADER = `
        <tr>
            <th>Código</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Subcategoria</th>
            <th>Descrição</th>
            <th>Fabricante</th>
            <th>Modelo</th>
            <th>Cor</th>
            <th>Peso (kg)</th>
            <th>Largura (cm)</th>
            <th>Altura (cm)</th>
            <th>Profundidade (cm)</th>
            <th>Unidade</th>
            <th>Cadastro</th>
            <th>Moeda</th>
            <th>Valor</th>
            <th>Promoção</th>
            <th>Desconto (%)</th>
            <th>Acréscimo (%)</th>
            <th>Início Promoção</th>
            <th>Fim Promoção</th>
            <th>Atualização</th>
            <th>Origem</th>
            <th>Tipo Cliente</th>
            <th>Vendedor</th>
            <th>Observações</th>
            <th>Status</th>
            <th>Preço Efetivo</th>
            <th>Tem Preço</th>
        </tr>
    `;

    // Cabeçalhos antigos (produto/preço) permanecem para uso futuro, mas o modo padrão é FULL_TABLE_HEADER

    async function callEndpoint(endpoint, options = {}) {
        try {
            const response = await fetch(endpoint, options);
            if (!response.ok) {
                throw new Error('Não foi possível executar a operação.');
            }
            return await response.json();
        } catch (error) {
            statusElement.textContent = error.message;
            statusElement.style.color = '#dc2626';
            throw error;
        }
    }

    function setLoading(isLoading) {
        processProductsButton.disabled = isLoading;
        processPricesButton.disabled = isLoading;
        refreshButton.disabled = isLoading;
    }

    function setTableHeader(template) {
        tableHead.innerHTML = template;
    }

    function setProductOnlyMode(enabled) {
        const table = document.getElementById('products-table');
        if (enabled) table.classList.add('product-only');
        else table.classList.remove('product-only');
    }

    function setPriceMode(enabled) {
        const table = document.getElementById('products-table');
        if (enabled) table.classList.add('price-full');
        else table.classList.remove('price-full');
    }

    // não usar mais o modo cards por padrão
    function setCardsMode(enabled) {
        const table = document.getElementById('products-table');
        if (!enabled) table.classList.remove('cards');
    }

    function formatNumber(value, decimals = 2) {
        if (value === null || value === undefined) {
            return '—';
        }
        const numericValue = Number(value);
        if (!Number.isFinite(numericValue)) {
            return '—';
        }
        return numericValue.toLocaleString('pt-BR', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals,
        });
    }

    function formatCurrency(value) {
        const formatted = formatNumber(value);
        return formatted === '—' ? '—' : `R$ ${formatted}`;
    }

    function formatPercent(value) {
        if (value === null || value === undefined) {
            return '—';
        }
        const numericValue = Number(value);
        if (!Number.isFinite(numericValue)) {
            return '—';
        }
        return `${formatNumber(numericValue * 100, 2)}%`;
    }

    function formatText(value) {
        if (value === null || value === undefined) {
            return '—';
        }
        const text = String(value).trim();
        return text.length ? text : '—';
    }

    function formatMoeda(value) {
        if (value === null || value === undefined) {
            return '—';
        }
        const text = String(value).trim();
        return text.length ? text.toUpperCase() : '—';
    }

    function renderProducts(data) {
        setTableHeader(PRICE_TABLE_HEADER);
        setProductOnlyMode(false);
        setPriceMode(true);
        setCardsMode(false);
        tableBody.innerHTML = '';
        if (!data.length) {
            tableBody.innerHTML = '<tr class="empty"><td colspan="23">Nenhum produto disponível.</td></tr>';
            return;
        }

        data.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td data-label="Código">${item.codigo}</td>
                <td data-label="Nome">${item.nome}</td>
                <td data-label="Categoria">${item.categoria ?? '—'}${item.subcategoria ? ' / ' + item.subcategoria : ''}</td>
                <td data-label="Fabricante">${item.fabricante ?? '—'}</td>
                <td data-label="Modelo">${item.modelo ?? '—'}</td>
                <td data-label="Cor">${item.cor ?? '—'}</td>
                <td data-label="Peso (kg)">${formatNumber(item.peso_kg, 3)}</td>
                <td data-label="Dimensões">${[item.largura_cm, item.altura_cm, item.profundidade_cm].map(v => formatNumber(v)).join(' × ')}</td>
                <td data-label="Moeda">${formatMoeda(item.moeda)}</td>
                <td data-label="Valor">${formatCurrency(item.valor)}</td>
                <td data-label="Promoção">${formatCurrency(item.valor_promocional)}</td>
                <td data-label="Desconto (%)">${formatPercent(item.percentual_desconto)}</td>
                <td data-label="Acréscimo (%)">${formatPercent(item.percentual_acrescimo)}</td>
                <td data-label="Início Promoção">${formatText(item.data_inicio_promocao)}</td>
                <td data-label="Fim Promoção">${formatText(item.data_fim_promocao)}</td>
                <td data-label="Atualização">${formatText(item.data_atualizacao)}</td>
                <td data-label="Origem">${formatText(item.origem)}</td>
                <td data-label="Tipo Cliente">${formatText(item.tipo_cliente)}</td>
                <td data-label="Vendedor">${formatText(item.vendedor_responsavel)}</td>
                <td data-label="Observações">${formatText(item.observacao)}</td>
                <td data-label="Status">${formatText(item.status)}</td>
                <td data-label="Preço Efetivo">${formatCurrency(item.preco_efetivo)}</td>
                <td data-label="Tem Preço">${item.tem_preco ? 'Sim' : 'Não'}</td>
            `;
            Array.from(row.children).forEach(td => td.setAttribute('title', td.textContent.trim()));
            tableBody.appendChild(row);
        });
    }

    // Render somente colunas de produto + metadados (sem valores de preço)
    function renderProductsOnly(data) {
        // rebuild header to product-only columns
        setTableHeader(PRODUCT_TABLE_HEADER);
        setProductOnlyMode(true);
        setPriceMode(false);
        setCardsMode(false);

        tableBody.innerHTML = '';
        if (!data.length) {
            tableBody.innerHTML = '<tr class="empty"><td colspan="14">Nenhum produto disponível.</td></tr>';
            return;
        }

        data.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td data-label="Código">${item.codigo}</td>
                <td data-label="Nome">${item.nome}</td>
                <td data-label="Categoria">${item.categoria ?? '—'}${item.subcategoria ? ' / ' + item.subcategoria : ''}</td>
                <td data-label="Fabricante">${item.fabricante ?? '—'}</td>
                <td data-label="Modelo">${item.modelo ?? '—'}</td>
                <td data-label="Cor">${item.cor ?? '—'}</td>
                <td data-label="Peso (kg)">${formatNumber(item.peso_kg, 3)}</td>
                <td data-label="Dimensões">${[item.largura_cm, item.altura_cm, item.profundidade_cm].map(v => formatNumber(v)).join(' × ')}</td>
                <td data-label="Atualização">${formatText(item.data_atualizacao)}</td>
                <td data-label="Origem">${formatText(item.origem)}</td>
                <td data-label="Tipo Cliente">${formatText(item.tipo_cliente)}</td>
                <td data-label="Vendedor">${formatText(item.vendedor_responsavel)}</td>
                <td data-label="Observações">${formatText(item.observacao)}</td>
                <td data-label="Status">${formatText(item.status)}</td>
            `;
            Array.from(row.children).forEach(td => td.setAttribute('title', td.textContent.trim()));
            tableBody.appendChild(row);
        });
    }

    // Tabela completa estilo planilha (todas as colunas)
    function renderFullTable(data) {
        setTableHeader(FULL_TABLE_HEADER);
        setProductOnlyMode(false);
        setPriceMode(true);
        tableBody.innerHTML = '';
        if (!data.length) {
            tableBody.innerHTML = '<tr class="empty"><td colspan="29">Nenhum produto disponível.</td></tr>';
            return;
        }
        data.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.codigo}</td>
                <td>${item.nome}</td>
                <td>${item.categoria ?? '—'}</td>
                <td>${item.subcategoria ?? '—'}</td>
                <td>${formatText(item.descricao)}</td>
                <td>${formatText(item.fabricante)}</td>
                <td>${formatText(item.modelo)}</td>
                <td>${formatText(item.cor)}</td>
                <td>${formatNumber(item.peso_kg, 3)}</td>
                <td>${formatNumber(item.largura_cm)}</td>
                <td>${formatNumber(item.altura_cm)}</td>
                <td>${formatNumber(item.profundidade_cm)}</td>
                <td>${formatText(item.unidade)}</td>
                <td>${formatText(item.data_cadastro)}</td>
                <td>${formatMoeda(item.moeda)}</td>
                <td>${formatCurrency(item.valor)}</td>
                <td>${formatCurrency(item.valor_promocional)}</td>
                <td>${formatPercent(item.percentual_desconto)}</td>
                <td>${formatPercent(item.percentual_acrescimo)}</td>
                <td>${formatText(item.data_inicio_promocao)}</td>
                <td>${formatText(item.data_fim_promocao)}</td>
                <td>${formatText(item.data_atualizacao)}</td>
                <td>${formatText(item.origem)}</td>
                <td>${formatText(item.tipo_cliente)}</td>
                <td>${formatText(item.vendedor_responsavel)}</td>
                <td>${formatText(item.observacao)}</td>
                <td>${formatText(item.status)}</td>
                <td>${formatCurrency(item.preco_efetivo)}</td>
                <td>${item.tem_preco ? 'Sim' : 'Não'}</td>
            `;
            tableBody.appendChild(row);
        });
    }

    // Tabela de produtos clássica
    function renderProductTable(data) {
        setTableHeader(PRODUCT_TABLE_HEADER);
        setProductOnlyMode(true);
        setPriceMode(false);
        tableBody.innerHTML = '';
        if (!data.length) {
            tableBody.innerHTML = '<tr class="empty"><td colspan="8">Nenhum produto disponível.</td></tr>';
            return;
        }
        data.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.codigo}</td>
                <td>${item.nome}</td>
                <td>${item.categoria ?? '—'}${item.subcategoria ? ' / ' + item.subcategoria : ''}</td>
                <td>${formatText(item.fabricante)}</td>
                <td>${formatText(item.modelo)}</td>
                <td>${formatText(item.cor)}</td>
                <td>${formatNumber(item.peso_kg, 3)}</td>
                <td>${[item.largura_cm, item.altura_cm, item.profundidade_cm].map(v => formatNumber(v)).join(' × ')}</td>
            `;
            tableBody.appendChild(row);
        });
    }

    async function refreshProducts() {
        setLoading(true);
        try {
            const result = await callEndpoint('/api/produtos-com-precos');
            renderFullTable(result.data ?? []);
            statusElement.textContent = `Encontrados ${result.data?.length ?? 0} produtos processados.`;
            statusElement.style.color = '#16a34a';
        } finally {
            setLoading(false);
        }
    }

    async function refreshProductsOnly() {
        setLoading(true);
        try {
            // Usa a listagem inclusiva (com join de preços) apenas para obter metadados
            // e renderiza somente o subconjunto de colunas solicitado (sem valores de preço)
            const result = await callEndpoint('/api/produtos-com-precos-inclusive');
            renderFullTable(result.data ?? []);
            statusElement.textContent = `Encontrados ${result.data?.length ?? 0} produtos processados.`;
            statusElement.style.color = '#16a34a';
        } finally {
            setLoading(false);
        }
    }

    async function process(endpoint) {
        setLoading(true);
        try {
            const result = await callEndpoint(endpoint, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                },
            });
            statusElement.textContent = `${result.message} Total: ${result.total}.`;
            statusElement.style.color = '#2563eb';
            return result;
        } finally {
            setLoading(false);
        }
    }

    processProductsButton.addEventListener('click', async () => {
        await process('/api/processar-produtos');
        // Após processar produtos, mostrar apenas colunas de produto (sem preços)
        await refreshProductsOnly();
    });
    processPricesButton.addEventListener('click', async () => {
        await process('/api/processar-precos');
        // after processing prices, show all products: valores ausentes aparecem como R$ 0,00
        await refreshProductsInclusive();
    });
    refreshButton.addEventListener('click', refreshProducts);

    refreshProducts();

    // Similar a refreshProducts, mas usa o endpoint inclusivo que retorna valor = 0 quando ausente
    async function refreshProductsInclusive() {
        setLoading(true);
        try {
            const result = await callEndpoint('/api/produtos-com-precos-inclusive');
            renderFullTable(result.data ?? []);
            statusElement.textContent = `Encontrados ${result.data?.length ?? 0} produtos processados.`;
            statusElement.style.color = '#16a34a';
        } finally {
            setLoading(false);
        }
    }
</script>
</body>
</html>
