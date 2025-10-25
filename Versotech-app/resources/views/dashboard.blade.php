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
            max-width: 1200px;
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
        thead {
            background: #1f2937;
            color: #fff;
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
                <th>Preço</th>
                <th>Promoção</th>
                <th>Desconto</th>
            </tr>
            </thead>
            <tbody id="products-body">
            <tr class="empty"><td colspan="11">Nenhum produto processado ainda.</td></tr>
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

    function renderProducts(data) {
        tableBody.innerHTML = '';
        if (!data.length) {
            tableBody.innerHTML = '<tr class="empty"><td colspan="11">Nenhum produto disponível.</td></tr>';
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
                <td data-label="Preço">${(item.valor !== null && item.valor !== undefined) ? `R$ ${formatNumber(item.valor)}` : (item.valor_promocional ? `R$ ${formatNumber(item.valor_promocional)}` : '—')}</td>
                <td data-label="Promoção">${(item.valor_promocional && item.valor_promocional !== null && item.valor_promocional !== item.valor) ? `R$ ${formatNumber(item.valor_promocional)}` : '—'}</td>
                <td data-label="Desconto">${item.percentual_desconto ? `${formatNumber(item.percentual_desconto * 100, 1)}%` : '—'}</td>
            `;
            tableBody.appendChild(row);
        });
    }

    // Render somente colunas de produto (sem preço)
    function renderProductsOnly(data) {
        // rebuild header to product-only columns
        const thead = document.querySelector('#products-table thead');
        thead.innerHTML = `
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Fabricante</th>
                <th>Modelo</th>
                <th>Cor</th>
                <th>Peso (kg)</th>
                <th>Dimensões (L×A×P cm)</th>
            </tr>
        `;

        tableBody.innerHTML = '';
        if (!data.length) {
            tableBody.innerHTML = '<tr class="empty"><td colspan="8">Nenhum produto disponível.</td></tr>';
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
            `;
            tableBody.appendChild(row);
        });
    }

    async function refreshProducts() {
        setLoading(true);
        try {
            const result = await callEndpoint('/api/produtos-com-precos');
            // ensure table header includes price columns
            const thead = document.querySelector('#products-table thead');
            thead.innerHTML = `
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Fabricante</th>
                <th>Modelo</th>
                <th>Cor</th>
                <th>Peso (kg)</th>
                <th>Dimensões (L×A×P cm)</th>
                <th>Preço</th>
                <th>Promoção</th>
                <th>Desconto</th>
            </tr>
            `;

            renderProducts(result.data ?? []);
            statusElement.textContent = `Encontrados ${result.data?.length ?? 0} produtos processados.`;
            statusElement.style.color = '#16a34a';
        } finally {
            setLoading(false);
        }
    }

    async function refreshProductsOnly() {
        setLoading(true);
        try {
            const result = await callEndpoint('/api/produtos');
            renderProductsOnly(result.data ?? []);
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
            // ensure table header includes price columns
            const thead = document.querySelector('#products-table thead');
            thead.innerHTML = `
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Fabricante</th>
                <th>Modelo</th>
                <th>Cor</th>
                <th>Peso (kg)</th>
                <th>Dimensões (L×A×P cm)</th>
                <th>Preço</th>
                <th>Promoção</th>
                <th>Desconto</th>
            </tr>
            `;

            renderProducts(result.data ?? []);
            statusElement.textContent = `Encontrados ${result.data?.length ?? 0} produtos processados.`;
            statusElement.style.color = '#16a34a';
        } finally {
            setLoading(false);
        }
    }
</script>
</body>
</html>
