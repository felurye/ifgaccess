# Como Contribuir

Obrigado pelo interesse em contribuir com o IFGAccess!

## Reportando problemas

Antes de abrir uma issue, verifique se o problema já foi relatado. Ao criar uma nova issue, descreva:

- O que você esperava que acontecesse
- O que aconteceu de fato
- Passos para reproduzir o problema
- Versão do sistema operacional e do Docker

## Sugerindo melhorias

Abra uma issue com o label `enhancement` descrevendo a melhoria e o motivo pelo qual ela seria útil.

## Enviando código

1. Faça um fork do repositório
2. Crie uma branch a partir da `main`:
   ```bash
   git checkout -b feature/minha-feature
   ```
3. Faça suas alterações
4. Garanta que o sistema ainda sobe corretamente com `docker-compose up --build`
5. Faça o commit seguindo o padrão [Conventional Commits](https://www.conventionalcommits.org/pt-br):
   ```bash
   git commit -m "feat: adiciona nova funcionalidade"
   git commit -m "fix: corrige comportamento de checkout"
   git commit -m "docs: atualiza instruções de instalação"
   ```
6. Abra um Pull Request descrevendo o que foi alterado e o motivo

## Padrão de commits

| Prefixo    | Uso                                                |
| ---------- | -------------------------------------------------- |
| `feat`     | Nova funcionalidade                                |
| `fix`      | Correção de bug                                    |
| `docs`     | Alterações em documentação                         |
| `refactor` | Refatoração sem mudança de comportamento           |
| `chore`    | Tarefas de manutenção (dependências, configuração) |

## Dúvidas

Abra uma [issue](https://github.com/felurye/ifgaccess/issues) com o label `question`.
