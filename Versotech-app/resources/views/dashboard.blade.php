<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processamento de Produtos e Preços</title>
    <style>
        :root {
            color-scheme: light;
            --gradient-start: #f3f5ff;
            --gradient-end: #e0e7ff;
            --surface: #ffffff;
            --surface-muted: rgba(255, 255, 255, 0.85);
            --text-primary: #111827;
            --text-secondary: #4b5563;
            --accent: #2563eb;
            --accent-strong: #1d4ed8;
            --border-subtle: #e5e7eb;
            --shadow-lg: 0 24px 55px rgba(15, 23, 42, 0.12);
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, 'Helvetica Neue', sans-serif;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at top left, rgba(96, 165, 250, 0.18), transparent 55%),
                        radial-gradient(circle at bottom right, rgba(129, 140, 248, 0.22), transparent 55%),
                        linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
        }
        .page {
            width: min(1220px, 92vw);
            margin: 0 auto;
            padding: clamp(1.5rem, 3vw + 1rem, 3.5rem) 0 4rem;
        }
        header.page-header {
            display: flex;
            align-items: center;
            gap: 1.75rem;
            margin-bottom: 2.75rem;
        }
        .page-header__icon {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.18), rgba(129, 140, 248, 0.28));
            display: grid;
            place-items: center;
            color: var(--accent);
            box-shadow: inset 0 0 0 1px rgba(37, 99, 235, 0.12);
        }
        h1 {
            font-size: clamp(1.75rem, 1.5vw + 1.6rem, 2.5rem);
            margin: 0 0 0.25rem;
        }
        .subtitle {
            margin: 0;
            font-size: 1rem;
            color: var(--text-secondary);
            max-width: 54ch;
            line-height: 1.6;
        }
        .card {
            background: var(--surface);
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            padding: clamp(1.75rem, 2vw + 1.5rem, 3rem);
            backdrop-filter: blur(8px);
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.85rem;
            margin-bottom: 2rem;
        }
        button {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            background: linear-gradient(135deg, var(--accent), var(--accent-strong));
            border: none;
            border-radius: 999px;
            color: #fff;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            padding: 0.8rem 1.95rem;
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }
        button svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 36px rgba(37, 99, 235, 0.28);
            filter: brightness(1.02);
        }
        button:focus-visible {
            outline: 3px solid rgba(37, 99, 235, 0.35);
            outline-offset: 2px;
        }
        button:disabled {
            cursor: not-allowed;
            background: #cbd5f5;
            box-shadow: none;
            color: #1f2937;
            transform: none;
            filter: none;
        }
        .status-wrapper {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.95rem 1.15rem;
            border-radius: 16px;
            background: var(--surface-muted);
            border: 1px solid rgba(148, 163, 184, 0.2);
            margin-bottom: 2rem;
            transition: border-color 0.2s ease, background-color 0.2s ease;
        }
        .status-icon {
            width: 14px;
            height: 14px;
            border-radius: 999px;
            background: #cbd5f5;
            box-shadow: 0 0 0 6px rgba(99, 102, 241, 0.08);
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        .status {
            min-height: 1.25rem;
            font-weight: 600;
            color: var(--text-secondary);
        }
        .status-wrapper[data-tone="info"] {
            background: rgba(219, 234, 254, 0.55);
            border-color: rgba(37, 99, 235, 0.25);
        }
        .status-wrapper[data-tone="info"] .status-icon {
            background: #2563eb;
            box-shadow: 0 0 0 6px rgba(37, 99, 235, 0.15);
        }
        .status-wrapper[data-tone="info"] .status {
            color: #1d4ed8;
        }
        .status-wrapper[data-tone="success"] {
            background: rgba(220, 252, 231, 0.65);
            border-color: rgba(22, 163, 74, 0.25);
        }
        .status-wrapper[data-tone="success"] .status-icon {
            background: #16a34a;
            box-shadow: 0 0 0 6px rgba(22, 163, 74, 0.15);
        }
        .status-wrapper[data-tone="success"] .status {
            color: #15803d;
        }
        .status-wrapper[data-tone="warning"] {
            background: rgba(254, 243, 199, 0.6);
            border-color: rgba(234, 179, 8, 0.25);
        }
        .status-wrapper[data-tone="warning"] .status-icon {
            background: #f59e0b;
            box-shadow: 0 0 0 6px rgba(234, 179, 8, 0.15);
        }
        .status-wrapper[data-tone="warning"] .status {
            color: #b45309;
        }
        .status-wrapper[data-tone="error"] {
            background: rgba(254, 226, 226, 0.65);
            border-color: rgba(220, 38, 38, 0.25);
        }
        .status-wrapper[data-tone="error"] .status-icon {
            background: #dc2626;
            box-shadow: 0 0 0 6px rgba(220, 38, 38, 0.15);
        }
        .status-wrapper[data-tone="error"] .status {
            color: #b91c1c;
        }
        .table-card {
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.18);
            background: var(--surface);
        }
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
            background: transparent;
        }
        thead {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.95), rgba(59, 130, 246, 0.9));
            color: #f8fafc;
        }
        th,
        td {
            padding: 0.9rem 1.05rem;
            text-align: left;
            border-bottom: 1px solid var(--border-subtle);
        }
        th {
            font-weight: 600;
            white-space: nowrap;
            letter-spacing: 0.01em;
        }
        tbody tr:nth-child(even) {
            background: rgba(241, 245, 249, 0.4);
        }
        tbody tr:hover {
            background: rgba(191, 219, 254, 0.4);
        }
        td {
            white-space: normal;
            word-break: normal;
            overflow-wrap: anywhere;
        }
        .table-wrapper::-webkit-scrollbar {
            height: 10px;
        }
        .table-wrapper::-webkit-scrollbar-track {
            background: rgba(226, 232, 240, 0.7);
            border-radius: 999px;
        }
        .table-wrapper::-webkit-scrollbar-thumb {
            background: rgba(37, 99, 235, 0.35);
            border-radius: 999px;
        }
        .empty {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--text-secondary);
            background: rgba(248, 250, 252, 0.8);
        }
        #products-table.product-only th {
            font-size: 0.9rem;
        }
        #products-table.product-only td {
            font-size: 0.9rem;
            padding: 0.6rem 0.75rem;
        }
        #products-table.price-full td:nth-child(7),
        #products-table.price-full td:nth-child(10),
        #products-table.price-full td:nth-child(11),
        #products-table.price-full td:nth-child(12),
        #products-table.price-full td:nth-child(13),
        #products-table.price-full td:nth-child(22),
        #products-table.price-full td:nth-child(23) {
            text-align: right;
        }
        #products-table.cards {
            width: 100%;
        }
        #products-table.cards thead {
            display: none;
        }
        #products-table.cards tbody {
            display: block;
        }
        #products-table.cards tbody tr {
            display: grid;
            grid-template-columns: repeat(4, minmax(180px, 1fr));
            gap: 0.65rem 1.75rem;
            background: rgba(248, 250, 252, 0.9);
            border-radius: 16px;
            padding: 1.1rem 1.25rem;
            margin-bottom: 1.2rem;
            border: 1px solid rgba(226, 232, 240, 0.7);
        }
        #products-table.cards td {
            border: none;
            white-space: normal;
            overflow: visible;
            text-overflow: clip;
            line-height: 1.35;
        }
        #products-table.cards td::before {
            content: attr(data-label);
            display: block;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.3rem;
        }
        @media (max-width: 1200px) {
            #products-table.cards tbody tr {
                grid-template-columns: repeat(3, minmax(180px, 1fr));
            }
        }
        @media (max-width: 900px) {
            #products-table.cards tbody tr {
                grid-template-columns: repeat(2, minmax(160px, 1fr));
            }
            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }
            thead tr {
                display: none;
            }
            tbody tr {
                margin-bottom: 1.25rem;
                background: rgba(255, 255, 255, 0.92);
                border-radius: 16px;
                padding: 1.2rem 1.35rem;
                box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
                border: 1px solid rgba(226, 232, 240, 0.7);
            }
            tbody td {
                border: none;
                position: relative;
                padding-left: 48%;
                white-space: normal;
            }
            tbody td::before {
                position: absolute;
                left: 1.25rem;
                width: 40%;
                white-space: nowrap;
                font-weight: 600;
                color: var(--text-secondary);
                content: attr(data-label);
            }
        }
        @media (max-width: 640px) {
            header.page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .page-header__icon {
                width: 56px;
                height: 56px;
            }
            button {
                width: 100%;
                justify-content: center;
            }
            tbody td {
                padding-left: 58%;
            }
            tbody td::before {
                width: 46%;
            }
        }
    </style>
</head>
<body>
<div class="page">
    <header class="page-header">
        <div class="page-header__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 7.5C4 6.11929 5.11929 5 6.5 5H9.75C10.1642 5 10.5 5.33579 10.5 5.75V8.25C10.5 9.49264 11.5074 10.5 12.75 10.5H18.25C19.4926 10.5 20.5 11.5074 20.5 12.75V16.5C20.5 17.8807 19.3807 19 18 19H6C4.61929 19 3.5 17.8807 3.5 16.5V7.5Z" stroke="currentColor" stroke-width="1.5"/>
                <path d="M13 5L15 3M18 8L20 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M8 15H9.5M12 15H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        </div>
        <div>
            <h1>Transformação de Produtos e Preços</h1>
            <p class="subtitle">Procure, processe e visualize rapidamente produtos com informações comerciais completas e prontas para apresentação.</p>
        </div>
    </header>

    <div class="card">
        <div class="actions">
            <button id="process-products">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M4 5H10L12 9H20L18 19H6L4 5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M9 13H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Processar Produtos
            </button>
            <button id="process-prices">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M5.75 5.75H18.25V18.25H5.75V5.75Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M9 9H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M9 12H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M9 15H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Processar Preços
            </button>
            <button id="refresh-list">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M6 7H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M6 12H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M6 17H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M3 7H3.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M3 12H3.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M3 17H3.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Listar Produtos com Preços
            </button>
        </div>

        <div class="status-wrapper" data-tone="neutral">
            <span class="status-icon" id="status-indicator" aria-hidden="true"></span>
            <span class="status" id="status">Aguardando ações.</span>
        </div>

        <div class="table-card">
            <div class="table-wrapper">
                <table id="products-table">
                    <thead>
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
                    </thead>
                    <tbody id="products-body">
                    <tr class="empty"><td colspan="29">Nenhum produto processado ainda.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const statusElement = document.getElementById('status');
    const statusWrapper = document.querySelector('.status-wrapper');
    const processProductsButton = document.getElementById('process-products');
    const processPricesButton = document.getElementById('process-prices');
    const refreshButton = document.getElementById('refresh-list');
    const tableBody = document.getElementById('products-body');
    const tableHead = document.querySelector('#products-table thead');

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

    const PRICE_TABLE_HEADER = `
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
    `;

    const PRODUCT_TABLE_HEADER = `
        <tr>
            <th>Código</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Fabricante</th>
            <th>Modelo</th>
            <th>Cor</th>
            <th>Peso (kg)</th>
            <th>Dimensões (L×A×P cm)</th>
            <th>Atualização</th>
            <th>Origem</th>
            <th>Tipo Cliente</th>
            <th>Vendedor</th>
            <th>Observações</th>
            <th>Status</th>
        </tr>
    `;

    function updateStatus(message, tone = 'neutral') {
        statusWrapper.dataset.tone = tone;
        statusElement.textContent = message;
    }

    async function callEndpoint(endpoint, options = {}) {
        try {
            const response = await fetch(endpoint, options);
            if (!response.ok) {
                throw new Error('Não foi possível executar a operação.');
            }
            return await response.json();
        } catch (error) {
            updateStatus(error.message, 'error');
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
            updateStatus('Nenhum produto disponível.', 'warning');
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
        updateStatus(`Encontrados ${data.length} produtos processados.`, 'success');
    }

    function renderProductsOnly(data) {
        setTableHeader(PRODUCT_TABLE_HEADER);
        setProductOnlyMode(true);
        setPriceMode(false);
        setCardsMode(false);

        tableBody.innerHTML = '';
        if (!data.length) {
            tableBody.innerHTML = '<tr class="empty"><td colspan="14">Nenhum produto disponível.</td></tr>';
            updateStatus('Nenhum produto disponível.', 'warning');
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
        updateStatus(`Encontrados ${data.length} produtos processados.`, 'success');
    }

    function renderFullTable(data) {
        setTableHeader(FULL_TABLE_HEADER);
        setProductOnlyMode(false);
        setPriceMode(true);
        tableBody.innerHTML = '';
        if (!data.length) {
            tableBody.innerHTML = '<tr class="empty"><td colspan="29">Nenhum produto disponível.</td></tr>';
            updateStatus('Nenhum produto disponível.', 'warning');
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
        updateStatus(`Encontrados ${data.length} produtos processados.`, 'success');
    }

    function renderProductTable(data) {
        setTableHeader(PRODUCT_TABLE_HEADER);
        setProductOnlyMode(true);
        setPriceMode(false);
        tableBody.innerHTML = '';
        if (!data.length) {
            tableBody.innerHTML = '<tr class="empty"><td colspan="8">Nenhum produto disponível.</td></tr>';
            updateStatus('Nenhum produto disponível.', 'warning');
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
        updateStatus(`Encontrados ${data.length} produtos processados.`, 'success');
    }

    async function refreshProducts() {
        setLoading(true);
        updateStatus('Carregando produtos com preços...', 'info');
        try {
            const result = await callEndpoint('/api/produtos-com-precos');
            const data = result.data ?? [];
            renderFullTable(data);
        } finally {
            setLoading(false);
        }
    }

    async function refreshProductsOnly() {
        setLoading(true);
        updateStatus('Carregando catálogo de produtos...', 'info');
        try {
            const result = await callEndpoint('/api/produtos-com-precos-inclusive');
            renderProductsOnly(result.data ?? []);
        } finally {
            setLoading(false);
        }
    }

    async function refreshProductsInclusive() {
        setLoading(true);
        updateStatus('Atualizando produtos e preços...', 'info');
        try {
            const result = await callEndpoint('/api/produtos-com-precos-inclusive');
            renderFullTable(result.data ?? []);
        } finally {
            setLoading(false);
        }
    }

    async function process(endpoint) {
        setLoading(true);
        updateStatus('Processando solicitação...', 'info');
        try {
            const result = await callEndpoint(endpoint, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                },
            });
            updateStatus(`${result.message} Total: ${result.total}.`, 'success');
            return result;
        } finally {
            setLoading(false);
        }
    }

    processProductsButton.addEventListener('click', async () => {
        await process('/api/processar-produtos');
        await refreshProductsOnly();
    });

    processPricesButton.addEventListener('click', async () => {
        await process('/api/processar-precos');
        await refreshProductsInclusive();
    });

    refreshButton.addEventListener('click', refreshProducts);

    refreshProducts();
</script>
</body>
</html>
