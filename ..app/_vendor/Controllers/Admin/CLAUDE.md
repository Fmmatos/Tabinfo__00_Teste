# Controllers Admin - Documentação Completa

## VISÃO GERAL DO SISTEMA ADMIN

O sistema admin é composto por **3 controladores fundamentais** que trabalham em conjunto:

1. **__LoginController_Admin.php** - Autenticação e segurança
2. **__AdminController_Admin.php** - CRUD genérico para todos os módulos  
3. **__MenuAdminController_Admin.php** - Criação dinâmica de módulos e tabelas

**IMPORTANTE**: O sistema é totalmente **dinâmico e genérico**. Um único conjunto de controladores gerencia TODOS os módulos do admin através de configurações JSON.

---

# 1. __LoginController_Admin.php

## VISÃO GERAL
Controlador responsável pela **autenticação e segurança** do painel administrativo.

## FUNCIONALIDADES

### index()
- Retorna página de login
- Status 200 para verificar se está ativo

### login()
- **Request**: __LoginRequest_Admin (validação automática)
- **Model**: Users_Admin
- **Token**: adminAuth
- Usa __LoginService::login() genérico
- Retorna token JWT para autenticação

### forget_password()
- Gera código de 5 dígitos
- Envia email com código via __ForgetPasswordMail_Admin
- Usa __LoginService::forget_password() genérico

### forget_password_verify_code()
- Verifica código enviado por email
- Valida tempo de expiração
- Usa __LoginService::forget_password_verify_code()

### forget_password_update()
- Atualiza senha após código validado
- Aplica Hash na nova senha
- Usa __LoginService::forget_password_update()

### logout()
- Remove token do banco
- Limpa autenticação
- Token: adminAuth
- Usa __LoginService::logout()

## SEGURANÇA
- Todas as senhas são hasheadas com bcrypt
- Tokens JWT com expiração configurável
- Códigos de recuperação expiram em 15 minutos
- Validação de força de senha via __PasswordRule

---

# 2. __AdminController_Admin.php

## VISÃO GERAL
Controlador **principal e genérico** do admin. Gerencia CRUD de **TODOS os módulos** dinamicamente.

## FLUXO DE DADOS
```
Requisição → YMenuAdmin_Admin::json($module) → Configuração JSON → CRUD Dinâmico
```

## FUNCIONALIDADES PRINCIPAIS

### index()
**Lista registros de qualquer módulo**
- **Parâmetros**: 
  - `$module` - ID do módulo no menu_admin
  - `$type` - Tipo do módulo (0=normal, 1=settings)
  - `$dashboard` - Se é chamado do dashboard
- **Recursos**:
  - Paginação automática
  - Busca com cookies de persistência
  - Ordenação dinâmica
  - Seleção múltipla para ações em lote
- **Tratamento especial**:
  - `type=1` → Redireciona para x_settings
  - Reseta cookies quando `init__=1`

### create_edit()
**Formulário de criação/edição**
- **CREATE**: Campos vazios com valores padrão
- **EDIT**: Busca registro por ID e preenche campos
- **Processamento**:
  - Alinha campos em left/right
  - Carrega relacionamentos (querys)
  - Aplica __Resource::fields_create_edit()
- **Validações**:
  - Verifica permissões
  - Campos obrigatórios
  - Tipos de dados

### store()
**Cria novo registro**
- **Transação**: Usa DB::beginTransaction()
- **Hooks**:
  - `store_pre` - Antes de salvar
  - `store_pos` - Após salvar
- **Tratamento**:
  - __Resource::fields_store_update() processa campos
  - Upload de imagens automático
  - Conversão de tipos (date, price, etc)

### update()
**Atualiza registro existente**
- **Segurança**: Verifica se ID existe
- **Hooks**:
  - `update_pre` - Antes de atualizar
  - `update_pos` - Após atualizar
- **Processamento**: Idêntico ao store()

### delete()
**Deleta registros**
- **Modos**:
  - Individual: `$id` específico
  - Múltiplo: Array `sel[]`
  - Todos: `sel_all_all=1`
- **Proteções hardcoded**:
  - `orders_status`: IDs 1-100 protegidos
  - `users`: IDs 1-2 (admins) protegidos
- **Hooks**:
  - `delete_pre` - Antes de deletar
  - `delete_pos` - Após deletar

### actions()
**Ações especiais**
- `order` - Reordenação drag-and-drop
- `items_page_update` - Altera itens por página
- Outras ações customizadas via __ActionsService

## SISTEMA DE PERMISSÕES

### auth_permissions()
**Verifica permissões do usuário**
- **Admin master** (id=1 ou 2): Acesso total
- **permissions_all=1**: Acesso total
- **permissions JSON**: Lista de módulos permitidos
- **Ações verificadas**:
  - `list` - Sempre permitido se tem acesso ao módulo
  - `edit` - Criar/editar
  - `delete` - Deletar
  - `hide` - Ver módulos ocultos

## SISTEMA DE COLUNAS

### columns()
**Define colunas visíveis na tabela/formulário**
- **Colunas especiais**:
  - `sel` - Checkbox de seleção
  - `active` - Status ativo/inativo
  - `order` - Ordenação
  - `star_1-5` - Sistema de favoritos
- **Alinhamento**:
  - Sufixo `-l`: Alinha à esquerda
  - Sufixo `-c`: Centralizado (padrão)
  - Sufixo `-r`: Alinha à direita

### columns_extra()
**Adiciona colunas extras**
- Definidas em `columns_extra` do JSON
- Separadas por vírgula
- Aparecem como texto simples

## SISTEMA DE QUERYS

### querys()
**Carrega dados relacionados para selects/checkboxes**
- **Autocomplete**: Busca sob demanda
- **Categorias**: Hierarquia com subcategorias
- **Filtros**:
  - `type_items` - Filtra por tipo
  - `filter` - Código PHP customizado
  - `orderby` - Ordenação customizada
- **Otimização**: Carrega apenas IDs necessários

## TRATAMENTO DE DADOS

### treatment()
**Processa dados antes de exibir**
- **table**: Prepara para listagem
- **create_edit**: 
  - Remove campos com `|->no_new` (em criação)
  - Remove campos com `|->no_edit` (em edição)

### align()
**Organiza campos em colunas**
- Separa em `left` e `right`
- Respeita propriedade `align` do campo
- Padrão: left

## X_SETTINGS (CONFIGURAÇÕES GLOBAIS)

### x_settings()
**Exibe configurações globais**
- Usa modelo XSettings
- Campos salvos como chave-valor
- Processamento especial para imagens

### x_settings__update()
**Salva configurações globais**
- **Validações**:
  - Campos required
  - Tipos de dados
- **Tratamento especial**:
  - `image_sharing`: Valida dimensões máximas (200x200)
  - Salva mime, width e height automaticamente
- **Conversões**:
  - `date` → Y-m-d
  - `datetime-local` → Y-m-d H:i:s
  - `price` → decimal
  - `checkbox` → JSON

---

# 3. __MenuAdminController_Admin.php

## VISÃO GERAL
Controlador do **núcleo de criação dinâmica** do sistema. Gerencia a criação automática de:
- Modelos (Models)
- Tabelas no banco de dados
- Colunas e índices
- Foreign keys e relacionamentos
- Migrações

**ACESSO**: Apenas usuário master (id=1)

## FUNCIONALIDADES PRINCIPAIS

### 1. GERENCIAMENTO DE MÓDULOS

#### index()
- Lista todos os módulos do admin
- Exclui módulo id=1 (próprio menu)
- Retorna tags de categorização

#### create_edit()
- **CREATE**: Estrutura padrão de campos
- **EDIT**: Informações detalhadas da tabela:
  - Colunas com tipos e propriedades
  - Índices e foreign keys
  - SQL de criação da tabela

#### store() / update()
- Salva configuração do módulo
- Chama `json__write()` para persistir em JSON
- Executa `treatment_pre_save()` para criar/atualizar estrutura

#### delete()
- Remove módulos (individual ou lote)
- Não deleta tabelas do banco

## 2. CRIAÇÃO DINÂMICA DE ESTRUTURA

### treatment_pre_save()
**Função mais importante - Orquestra toda criação**

#### FLUXO COMPLETO:
1. **Processamento de campos** com flags especiais
2. **Criação/atualização do Model**
3. **Criação/alteração da tabela**
4. **Criação de colunas**
5. **Criação de índices e foreign keys**
6. **Geração de migrações**

### DECISÃO CRÍTICA: MODELO BASE

```php
// CHECK IF TABLE ENDS WITH _categories TO USE APPROPRIATE BASE MODEL
$CATEGORIES = (substr($table, -11) === '_categories');
$model_itens = $CATEGORIES ? 
    DIR_F.'/_vendor/Models/ItemsCategories.php' : 
    DIR_F.'/_vendor/Models/Items.php';
```

### TABELAS DE CATEGORIAS (_categories)

**Estrutura especial**:
```php
$t->bigIncrements('id');
$t->integer('active')->default(1);
$t->text('name');
$t->longText('image')->nullable();
$t->integer('type')->default(0);        // 0=principal, 1+=sub
$t->unsignedBigInteger('subcategories')->nullable();  // auto-ref
$t->integer('order')->default(999);
$t->timestamps();
```

**Relacionamentos automáticos**:
- `categories_array()` - Categoria pai (belongsTo)
- `subcategories_array()` - Subcategorias (hasMany)
- `{tabela_principal}()` - Itens da categoria

**NÃO cria relacionamentos duplicados** para coluna `subcategories`

### TABELAS NORMAIS

**Estrutura padrão**:
```php
$t->bigIncrements('id');
$t->integer('active')->default(1);
$t->text('name');
$t->longText('image')->nullable();
$t->string('type', 45)->nullable();
$t->integer('order')->default(999);
$t->timestamps();
```

**Relacionamentos**: Criados dinamicamente baseado em foreign keys

## 3. CRIAÇÃO DE COLUNAS

### TIPOS POR create_column:
- `bigint` → bigInteger
- `bigint_key` → unsignedBigInteger + foreign key
- `int` → integer  
- `date` → date
- `datetime` → dateTime
- `month` → string(7)
- `decimal` → decimal(10,2)
- `varchar_50` → string(50)
- `varchar` → string(255)
- `text_long` → longText
- `json` → longText

### TIPOS POR type DO CAMPO:
- `categories`, `subcategories`, `number`, `select` → integer
- `price` → decimal(10,2)
- `date` → date
- `datetime-local` → dateTime
- `color` → string(10)
- `file`, `checkbox` → longText
- `textarea`, `address` → text
- `phone` → string(15)
- `zipcode` → string(10)
- `uf` → string(2)
- `json_fields` → longText

### CAMPOS ESPECIAIS POR NOME:
- `cpf` → string(14)
- `cnpj`, `cpf_cnpj` → string(18)

## 4. OPERAÇÕES DE BANCO DE DADOS

### COLUNAS

#### column_update()
Altera propriedades de colunas:
- **Field**: Renomear
- **Type**: Mudar tipo
- **Null**: Alterar nullable
- **Default**: Valor padrão
- **Key**: Índices (PRI, UNI, MUL)
- **Extra**: auto_increment

#### column_reorder()
- Reordena colunas mantendo propriedades
- Usa AFTER para posicionamento

### ÍNDICES

#### indexes_create()
- Normal ou UNIQUE
- Suporta múltiplas colunas
- Nome automático: `{tabela}__{colunas}__index`

#### indexes_rename()
- Mantém tipo e colunas
- Apenas altera nome

#### indexes_delete()
- Verifica se não é parte de foreign key
- Remove do banco

### FOREIGN KEYS

#### foreign_key_create()
```sql
ON DELETE SET NULL ON UPDATE CASCADE -- padrão
```
- Nome: `{tabela}__{coluna}__foreign`
- Cria índice automaticamente

#### foreign_key_update()
- Altera coluna local/referenciada
- Modifica regras ON DELETE/UPDATE

#### foreign_key_delete()
- Remove constraint
- Tenta remover índice associado

## 5. SISTEMA DE RELACIONAMENTOS

### CRIAÇÃO AUTOMÁTICA

#### BELONGS TO (N:1)
Para cada foreign key:
```php
public function {coluna_sem_id}()
{
    return $this->belongsTo('Root\Models\{Modelo}', '{coluna}', 'id');
}
```

#### HAS MANY (1:N)
Para tabelas que referenciam:
```php
public function {tabela_referenciadora}()
{
    return $this->hasMany('Root\Models\{Modelo}', '{coluna_fk}', 'id');
}
```

#### SELF REFERENCES
```php
// Pai
public function {coluna}_parent()
{
    return $this->belongsTo('Root\Models\{Self}', '{coluna}', 'id');
}

// Filhos
public function {coluna}_children()
{
    return $this->hasMany('Root\Models\{Self}', '{coluna}', 'id');
}
```

## 6. SISTEMA JSON

### json__write()
**Salva em**: `_root/z_Json/menu_admin/{id}.json`

**Estrutura**:
```json
{
    "module": {...},      // YMenuAdmin_Admin
    "input": [...],       // Campos (base64 se configurado)
    "table": "nome",
    "info": [...]        // Configurações de exibição
}
```

**Base64**: Se `ADMIN__JSON__MENU_ADMIN_BASE64 = true`

## 7. MIGRAÇÕES

### Geração automática
- Usa __MigrateService::generateMigrationForTable()
- Cria backup diário
- Nome: `YYYY_MM_DD_HHMMSS_create_{tabela}_table.php`

---

# REGRAS E CONVENÇÕES IMPORTANTES

## NOMENCLATURA

### Tabelas e Modelos
- **Modelo**: PascalCase → `CustomersCategories`
- **Tabela**: snake_case → `customers_categories`
- **Conversão**: `/(?<!^)[A-Z]/` com underscore

### Tabelas especiais
- **Prefixo `api_`**: Adiciona `_` no banco
- **Sufixo `_categories`**: Usa modelo ItemsCategories

## CAMPOS ESPECIAIS

### Flags (sufixo __)
- `campo__` → `|->no_search` (não pesquisável)
- `campo__` → `|->no_save` (não salvo)

### Tipos especiais
- `editor` → Salvo em tabela `{tabela}_editor`
- `info` → Apenas visual, não cria coluna
- `column` → Agrupa campos no formulário

## FOREIGN KEYS

### Criação automática
Quando `create_column = 'bigint_key'` + `options` (ID do menu)

### Nomenclatura
- Constraint: `{tabela}__{coluna}__foreign`
- Índice: `{tabela}__{coluna}__index`

### Regras padrão
```sql
ON DELETE SET NULL
ON UPDATE CASCADE
```

## CATEGORIAS

### Estrutura hierárquica
- `type = 0` → Categoria principal
- `type > 0` → Subcategoria (nível)
- `subcategories` → ID da categoria pai

### Relacionamentos
- NÃO duplicar para `subcategories`
- Manter referências corretas no modelo

## BACKUPS

### Models
- Pasta: `_root/Models/old/`
- Nome: `YYYY_MM_DD_HH_II_SS__{Modelo}.php`

### Migrações
- Backup diário automático
- Mantém histórico de alterações

---

# EXEMPLOS PRÁTICOS

## Criar módulo de produtos
```php
// Nome: Products
// Tabela: products
// Modelo base: Items
// Relacionamentos: Criados por foreign keys
```

## Criar categorias de produtos
```php
// Nome: ProductsCategories
// Tabela: products_categories
// Modelo base: ItemsCategories
// Relacionamentos automáticos:
//   - categories_array()
//   - subcategories_array()
//   - products()
```

## Adicionar foreign key
```php
// Campo: category_id
// create_column: bigint_key
// options: [ID do menu de categorias]
// Cria automaticamente:
//   - Coluna unsignedBigInteger
//   - Foreign key
//   - Relacionamento category()
```

---

# DEBUGGING E TROUBLESHOOTING

## Forçar recriação
```php
$request['save'] = 'force_create_model_again';
```

## Verificar estrutura
```sql
SHOW COLUMNS FROM tabela;
SHOW INDEX FROM tabela;
SHOW CREATE TABLE tabela;
SELECT * FROM information_schema.KEY_COLUMN_USAGE 
WHERE TABLE_NAME = 'tabela';
```

## Logs e erros
- Transações com rollback automático
- Try/catch em todas operações críticas
- `$_GET['errors']` para mensagens ao usuário

---

# AVISOS CRÍTICOS

⚠️ **PERMISSÕES**: Apenas master (id=1) em __MenuAdminController_Admin

⚠️ **BACKUPS**: Sempre criados antes de sobrescrever

⚠️ **CATEGORIAS**: Comportamento especial para `*_categories`

⚠️ **FOREIGN KEYS**: Deletar índice verifica constraint

⚠️ **MIGRAÇÕES**: Não editar manualmente

⚠️ **TRANSAÇÕES**: Todas operações de banco em transaction

---

# FLUXO COMPLETO DE CRIAÇÃO DE MÓDULO

1. **Admin cria módulo** no menu_admin
2. **Define campos** e configurações
3. **Salva** → dispara treatment_pre_save()
4. **Sistema cria**:
   - Tabela no banco
   - Modelo em _root/Models/
   - JSON em _root/z_Json/
   - Migração em database/migrations/
5. **CRUD automático** disponível via __AdminController_Admin
6. **Permissões** configuráveis por usuário

---

# INTEGRAÇÃO ENTRE CONTROLADORES

```
__LoginController_Admin (autenticação)
    ↓
__AdminController_Admin (CRUD genérico)
    ↓
__MenuAdminController_Admin (estrutura dinâmica)
    ↓
YMenuAdmin_Admin::json() (configuração)
    ↓
Modelo dinâmico (Items ou ItemsCategories)
```

Cada controlador tem sua responsabilidade específica, mas trabalham em conjunto para criar um sistema admin completamente dinâmico e extensível.