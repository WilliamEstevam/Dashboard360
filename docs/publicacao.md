# Publicação e testes

[Voltar ao README](../README.md)

## Demonstração online

- URL: https://dashboard360-williamestevam.onrender.com
- Hospedagem: Render, serviço web Docker no plano **Free**.
- O `Dockerfile` utiliza PHP 8.5 com Apache; o `render.yaml` permite reproduzir a configuração.
- A imagem inclui somente a aplicação. Configurações locais, uploads, capturas e arquivos temporários ficam fora dela.
- Dados e alterações de visitantes são demonstrativos e ficam na sessão. Reinicializações podem apagar essas sessões.
- Após 15 minutos sem tráfego, o serviço gratuito pode suspender; o próximo acesso pode levar cerca de um minuto.

[Configurar outra instância gratuita](https://render.com/deploy?repo=https://github.com/WilliamEstevam/Dashboard360) · [Limites do plano gratuito](https://render.com/docs/free)

## Testes automáticos

O fluxo [Testes](https://github.com/WilliamEstevam/Dashboard360/actions/workflows/ci.yml) roda em atualizações da `main` e pull requests. Ele constrói a imagem Docker, verifica a sintaxe PHP, inicia o Apache e executa os testes HTTP existentes.

Para verificar uma instância local com Python instalado:

```bash
python scripts/smoke_test.py http://127.0.0.1:8080/index.php
```

## Capa e gravação

- [Capa de compartilhamento](images/social-preview.jpg), em 1280 × 640, pronta para a opção **Settings → Social preview** do GitHub.
- [Fonte editável da capa](images/social-preview.svg), que utiliza a captura `painel-controle.jpg` da mesma pasta.
- [GIF demonstrativo](images/demo.gif), com navegação real em dados sintéticos e duração de 24 segundos.

No perfil do GitHub, **Customize your pins** permite destacar o repositório Dashboard360.
