# Dashboard360

Plataforma para centralizar indicadores e acompanhar tendências de atendimento em call center.

Desenvolvi o projeto de forma **individual e autônoma**, durante minha passagem como **jovem aprendiz na Tahto**. O sistema entrou em operação em **todo o setor de retenção**, reunindo indicadores antes distribuídos em planilhas Excel com tratamentos diferentes.

## Principais funcionalidades

- Indicadores de atendimento, retenção e cancelamento, com gráficos, metas e ranking por operador.
- Filtros por período e liderança, detalhamento de chamadas e exportação de relatórios.
- Monitoramento de pausas, tempo logado, absenteísmo e banco de horas.
- Gestão de usuários, justificativas e comunicados da equipe.

## Tecnologias

**PHP · SQL Server · ODBC · JavaScript · Tailwind CSS · Chart.js · Python · Playwright**

O principal desafio foi desenvolver para o ambiente legado disponível, com **PHP 5.2 e SQL Server**. Escolhi o Tailwind CSS pela facilidade de aplicar e ajustar os detalhes visuais.

## Telas em destaque

| Metas e performance | Monitor WFM |
| :---: | :---: |
| ![Indicadores mensais e acompanhamento de metas com dados fictícios](docs/images/metas-performance.jpg) | ![Monitor WFM demonstrando o aviso de sincronização pausada](docs/images/monitor-wfm-offline.jpg) |

[![Ver galeria completa](docs/images/ver-galeria.svg)](docs/galeria.md)

## Monitor WFM

Também desenvolvi a automação em **Python com Playwright** que coletava dados de uma plataforma interna e atualizava o SQL Server **a cada 30 segundos**.

Quando a base deixava de receber atualizações, a tela exibia **“Robô Offline”** e **“Sincronização Pausada”**. O script externo não está no repositório porque depende de acessos e informações sensíveis da integração original.

## Como executar

Esta versão de portfólio usa **dados inteiramente fictícios** e funciona sem banco ou automação externa.

**Pré-requisitos:** Git e PHP instalados e disponíveis no terminal. A demonstração foi verificada com PHP 8.5.

Abra o terminal na pasta em que deseja salvar o projeto e execute os três comandos, nesta ordem:

```bash
git clone https://github.com/WilliamEstevam/Dashboard360.git
cd Dashboard360
php -S 127.0.0.1:8080
```

Acesse [http://127.0.0.1:8080](http://127.0.0.1:8080) no navegador e clique em **Explorar demonstração**. As alterações ficam apenas na sessão do visitante.

Mantenha o terminal aberto enquanto usa o sistema. Para encerrar o servidor, pressione **Ctrl+C**.

---

Desenvolvido por [WilliamEstevam](https://github.com/WilliamEstevam) · [Licença MIT](LICENSE).
