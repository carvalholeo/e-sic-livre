# Contribuindo

Antes de mais nada, obrigado por contibuir, você é demais!

Por favor, tire um tempo para revisar este documento para fazer o processo de contribuição fácil
e efetivo para todos os envolvidos.

## Relatando um problema

1. Tenha certeza de estar com a versão mais recente do e-SIC Livre e veja se o problema ainda existe.

2. Procure por issues similares. É possível que alguém já tenha encontrado este bug anteriormente.

3. Descreva informações detalhadas, incluindo: a versão do PHP, versão do e-SIC, o tipo de
sistema operacional e servidor Web, tipo de navegador e versão.

4. Descreva o erro e sua pilha, se possível. Um print para explicar o problema é muito bem-vindo.

5. Detalhe cada um dos passos para reprodução da falha.

## Solictando um recurso

1. Pesquise nas issues por pedidos de recursos similares. É possível que alguém já
tenha solicitado este recurso antes ou tenha feito uma pull request que ainda está sendo
analisada.

2. Faça uma explicação clara e detalhada do recurso que você quer e porquê
é importante adicionar. Tenha em mente que nós queremos recursos que serão úteis
para a maioria dos usuários e não somente para um pequeno grupo.

3. Se o recurso for complexo, considere escrever uma documentação inicial para
ela. Se o pedido for aceito, ele precisará ser documentado e
isto também vai nos ajudar a nos entendermos melhor.

4. Tente uma Pull Request. Se você é talentoso, comece escrevendo código. Se
você pudar codar, então isso acelerará o processo como um todo.

## Pull requests

1. Faça um fork do repositório.

2. Instale no seu ambiente.

3. Adicione um teste para suas mudanças. Somente refatoração e mudanças na documentação
não precisam de novos testes. Se você está adicionando funcionalidades ou corringindo bugs, nós precisamos
de um teste!

4. Faça o teste passar.

5. Faça o commit de suas mudanças. Se sua pull request corrigir uma issue específica, diga na mensagem do commit.
Aqui está um exemplo: `git commit -m "Fechando issue #42 – Correção de um bug idiota"`

6. Envie para seu fork e solicite uma pull request. Por favor, nos dê alguma
explicação do porquê você fez as mudanças que fez. Para novos recursos, tenha certeza de
explicar um caso de uso padrão para nós.

Algumas coisas que poderão aumentar as chances de que sua pull request seja aceita:

- Incluir os testes
- Escrevendo a documentação
- Fazer refêrencia para alguma issue aberta anteriormente
- Conformidade com GPDR (Europa) / LGPDP (Brasil)
- Conformidade com a Lei de Acesso à Informação

Baseado e adaptado de Yii2-Config, dispoível em [Wiki Yii2-Config](https://github.com/abhi1693/yii2-config/wiki/CONTRIBUTING.md).
