<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Exemplo de Tabela de Produtos</title>
    <style>
        :root {
            color-scheme: light dark;
        }

        body {
            font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 2rem;
            background-color: #f5f5f5;
            color: #1f2933;
        }

        h1 {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
        }

        .table-wrap {
            overflow-x: auto;
            max-width: 100%;
            background-color: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.12);
            padding: 1rem;
        }

        .tabela {
            width: 100%;
            min-width: 960px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .tabela thead {
            background: linear-gradient(135deg, #1f6feb, #2641c2);
            color: #ffffff;
        }

        .tabela th,
        .tabela td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .tabela tbody tr:nth-child(odd) {
            background-color: rgba(15, 23, 42, 0.02);
        }

        .tabela tbody tr:nth-child(even) {
            background-color: rgba(15, 23, 42, 0.04);
        }

        .col-codigo { width: 120px; max-width: 120px; }
        .col-nome { width: 220px; max-width: 220px; }
        .col-categoria { width: 180px; max-width: 180px; }
        .col-fabricante { width: 180px; max-width: 180px; }
        .col-modelo { width: 160px; max-width: 160px; }
        .col-cor { width: 140px; max-width: 140px; }
        .col-peso { width: 140px; max-width: 140px; }
        .col-dimensoes { width: 200px; max-width: 200px; }
        .col-atualizacao { width: 160px; max-width: 160px; }
        .col-origem { width: 140px; max-width: 140px; }
        .col-tipo-cliente { width: 160px; max-width: 160px; }
        .col-vendedor { width: 160px; max-width: 160px; }
        .col-observacoes { width: 220px; max-width: 220px; }
        .col-status { width: 140px; max-width: 140px; }

        @media (max-width: 768px) {
            body {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .table-wrap {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <h1>Inventário de Produtos</h1>
    <div class="table-wrap">
        <table class="tabela">
            <thead>
                <tr>
                    <th class="col-codigo">Código</th>
                    <th class="col-nome">Nome</th>
                    <th class="col-categoria">Categoria</th>
                    <th class="col-fabricante">Fabricante</th>
                    <th class="col-modelo">Modelo</th>
                    <th class="col-cor">Cor</th>
                    <th class="col-peso">Peso (kg)</th>
                    <th class="col-dimensoes">Dimensões (L×A×P cm)</th>
                    <th class="col-atualizacao">Atualização</th>
                    <th class="col-origem">Origem</th>
                    <th class="col-tipo-cliente">Tipo Cliente</th>
                    <th class="col-vendedor">Vendedor</th>
                    <th class="col-observacoes">Observações</th>
                    <th class="col-status">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-codigo">PRD-000123</td>
                    <td class="col-nome">Controlador Industrial Série X1000 com múltiplas entradas e saídas digitais</td>
                    <td class="col-categoria">Automação Industrial</td>
                    <td class="col-fabricante">Versotech Solutions</td>
                    <td class="col-modelo">VX-CTRL-X1000</td>
                    <td class="col-cor">Cinza Grafite</td>
                    <td class="col-peso">5,80</td>
                    <td class="col-dimensoes">45 × 12 × 38</td>
                    <td class="col-atualizacao">12/02/2024 09:41</td>
                    <td class="col-origem">Brasil</td>
                    <td class="col-tipo-cliente">Integrador</td>
                    <td class="col-vendedor">Mariana Alves</td>
                    <td class="col-observacoes">Instalar somente em painéis com ventilação forçada e prever redundância de alimentação.</td>
                    <td class="col-status">Disponível</td>
                </tr>
                <tr>
                    <td class="col-codigo">PRD-000214</td>
                    <td class="col-nome">Sensor de temperatura e umidade IP67 com transmissão LoRaWAN integrada</td>
                    <td class="col-categoria">Sensores Ambientais</td>
                    <td class="col-fabricante">ClimaTech Labs</td>
                    <td class="col-modelo">CTH-LW-900</td>
                    <td class="col-cor">Branco Polar</td>
                    <td class="col-peso">0,45</td>
                    <td class="col-dimensoes">10 × 6 × 4</td>
                    <td class="col-atualizacao">28/01/2024 16:22</td>
                    <td class="col-origem">Argentina</td>
                    <td class="col-tipo-cliente">Revenda</td>
                    <td class="col-vendedor">Carlos Pereira</td>
                    <td class="col-observacoes">Acompanha kit de montagem em trilho DIN e antena reserva.</td>
                    <td class="col-status">Em trânsito</td>
                </tr>
                <tr>
                    <td class="col-codigo">PRD-000307</td>
                    <td class="col-nome">Módulo inversor trifásico com monitoramento remoto e relatórios em tempo real</td>
                    <td class="col-categoria">Energia Solar</td>
                    <td class="col-fabricante">SunPower Grid</td>
                    <td class="col-modelo">SPG-TRI-4500</td>
                    <td class="col-cor">Preto Carbônico</td>
                    <td class="col-peso">12,10</td>
                    <td class="col-dimensoes">52 × 18 × 42</td>
                    <td class="col-atualizacao">03/02/2024 11:05</td>
                    <td class="col-origem">China</td>
                    <td class="col-tipo-cliente">Instalador</td>
                    <td class="col-vendedor">Larissa Monteiro</td>
                    <td class="col-observacoes">Requer homologação prévia junto à concessionária local e firmware atualizado.</td>
                    <td class="col-status">Reservado</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
