# PomodoroGatos - Especificação do Projeto

## Visão Geral

Aplicação web de produtividade baseada na técnica Pomodoro, gamificada com sistema de cards colecionáveis e playlist de áudio ambiente. O objetivo é incentivar sessões de foco recorrentes através de recompensas aleatórias (cards) e acompanhamento visual de métricas.

---

## Stack Tecnológica

### Backend
| Tecnologia | Versão | Propósito |
|---|---|---|
| Laravel | 12 | Framework MVC, API, routing |
| PHP | 8+ | Linguagem backend |
| Inertia.js | 2.3.7 | Ponte SPA sem API REST |
| Laravel Fortify | - | Autenticação + 2FA |

### Frontend
| Tecnologia | Versão | Propósito |
|---|---|---|
| Vue 3 | ^3.5 | Framework reativo |
| Pinia | ^3.0 | Gerenciamento de estado |
| TailwindCSS | 4.1 | Estilização |
| Howler.js | 2.2 | Reprodução de áudio |
| reka-ui | 2.9 | Componentes primitivos (dialog, select, etc) |
| Lucide / Tabler Icons | - | Ícones |
| Vue Sonner | 2.0 | Toasts/notificações |
| vuedraggable | 4.1 | Drag & drop na playlist |
| Vite | 7.0 | Build + dev server |
| TypeScript | 5.2 | Tipagem |

### Infraestrutura
- Banco de dados MySQL/MariaDB
- Storage local para arquivos (áudios e imagens)
- Firebase (autenticação Google)

---

## Arquitetura
```
frontend (Vue 3 SPA via Inertia)
    ├── Pages (Index, Analytic, Login, admin/Dashboard)
    ├── Components (pomodoro, audio, dashboard, analytic, ui)
    ├── Stores (Pinia: Timer, User, Playlist, Catalog, Card, Analytics)
    ├── Composables (useAudioPlayer, useAppearance)
    └── Layouts (AppLayout, AdminLayout)

backend (Laravel)
    ├── Routes (web.php, api.php, pomodoro.php)
    ├── Controllers (Pomodoro, FocusSession, Audio, Catalog, Analytic, Auth, Dashboard/Card)
    ├── Actions (CreateFocusSessions, GiveCardToUser, GetHeatmap, GetFocusDist, GetTodayFocus)
    ├── Models (User, Card, UserCard, FocusSession, Audio)
    ├── Resources (CardResource)
    ├── Middleware (AuthAdmin)
    └── Enums (CardRarity, FocusSessionStatus)
```

Padrão **Action-based**: controllers delegam lógica de negócio para classes Action dedicadas, mantendo controllers finos e lógica testável.

---

## Modelos de Dados
### users
| Campo | Tipo | Observação |
|---|---|---|
| id | BIGINT | PK |
| username | VARCHAR | Nome de exibição |
| email | VARCHAR | Único, login |
| password | VARCHAR | Hash (hashed) |
| photo | VARCHAR | URL da foto de perfil |
| admin | BOOLEAN | Acesso ao painel admin |
| two_factor_* | TEXT | 2FA (Fortify) |
| remember_token | VARCHAR | Session |

### focus_sessions
| Campo | Tipo | Observação |
|---|---|---|
| id | BIGINT | PK |
| user_id | BIGINT | FK → users |
| duration | INT | Minutos da sessão |
| status | ENUM | `completed` / `interrupted` |
| date | DATE | Data da sessão |

### cards
| Campo | Tipo | Observação |
|---|---|---|
| id | BIGINT | PK |
| title | VARCHAR | Nome do card |
| rarity | ENUM | `common`, `uncommun`, `rare`, `epic`, `legendary` |
| image | VARCHAR | Nome do arquivo (storage/cards/) |

### user_cards
| Campo | Tipo | Observação |
|---|---|---|
| id | BIGINT | PK |
| user_id | BIGINT | FK → users |
| card_id | BIGINT | FK → cards |
| unlock_at | TIMESTAMP | Data de desbloqueio |

### audio
| Campo | Tipo | Observação |
|---|---|---|
| id | BIGINT | PK |
| user_id | BIGINT | FK → users |
| title | VARCHAR | Título do áudio |
| path | VARCHAR | Nome do arquivo (storage/audio/) |
| order | INT | Posição na playlist |

---

## Funcionalidades Detalhadas
### 1. Timer Pomodoro

**Componentes:** `Timer.vue`, `CentralIcon.vue`, `Loading.vue`, `DialogConfig.vue`

**Store:** `TimerStore.ts`

**Comportamento:**
1. Timer padrão de 50 minutos (configurável pelo usuário)
2. Ao clicar em iniciar:
   - Toca som de partida (`/starttimer.mp3`, 1.5s)
   - Inicia a playlist de áudio (`startPlaylist()`)
   - Atualiza o título da aba com o countdown (`Timer: MM:SS`)
   - Barra de progresso atualiza em tempo real
3. Durante o countdown, cada segundo decrementa `sec` e depois `min`
4. Ao zerar:
   - Toca som de conclusão (`/timer.mp3`, 10s)
   - Define título como "Timer Finalizado!"
   - Salva sessão com status `completed`
   - Reseta o timer para 50:00
5. Se pausado (clicar durante execução):
   - Salva sessão com status `interrupted`
6. Ao receber resposta do servidor com `completed`, exibe animação do card desbloqueado

**Endpoints:**
- `POST /pomodoro/newfocus` — salva sessão de foco (autenticado)

---

### 2. Sistema de Cards Colecionáveis

**Models:** `Card`, `UserCard`

**Action:** `GiveCardToUser.php`

**Comportamento:**
1. Ao completar uma sessão de foco, `CreateFocusSessions` invoca `GiveCardToUser`
2. Sorteia um card de todos os cards existentes com probabilidade ponderada por **peso** baseado na raridade:

| Raridade | Peso | ~Probabilidade |
|---|---|---|
| common | 65 | ~61.3% |
| rare | 12 | ~11.3% |
| uncommun | 10 | ~9.4% |
| epic | 5 | ~4.7% |
| legendary | 2 | ~1.9% |

3. Cria um registro em `user_cards` com a data de desbloqueio
4. Retorna o card sorteado para o frontend exibir a animação

**Componentes:** `DialogCatalog.vue`, `CardCat.vue` (pomodoro + dashboard), `UnlockCard.vue`

---

### 3. Catálogo de Cards
**Controller:** `CatalogController.php`
**Store:** `CatalogStore.ts`

**Comportamento:**
1. Busca todos os cards ordenados por raridade (`FIELD(rarity, ...)` — legendary primeiro)
2. Retorna coleção via `CardResource` com total
3. Frontend exibe cards desbloqueados e bloqueados (imagem `/images/bloqueado.png`)
4. Acessível via dialog na tela principal

**Endpoints:**
- `GET /pomodoro/getcatalog` — retorna catálogo completo (autenticado)

---

### 4. Player de Audio / Playlist

**Model:** `Audio`

**Controller:** `AudioController.php`

**Composable:** `useAudioPlayer.ts`

**Stores:** `PlaylistStore.ts`, `DialogUplaodAudio.ts`

**Comportamento:**
1. Usuário faz upload de arquivos `.mp3` com título personalizado
2. Limite de 20 áudios por playlist
3. Armazenados em `storage/app/public/audio/`
4. Ao iniciar o timer, playlist toca automaticamente do primeiro áudio (`startPlaylist()`)
5. Usa Howler.js com `html5: true` para streaming

**Controles do player:**
- Play / Pause (`togglePlay`)
- Próximo áudio (`nextAudio`)
- Áudio anterior (`prevAudio`)
- Mute (`toggleMute`)
- Controle de volume (`setVolume`, default 0.5)
- Exibição de tempo atual / duração total

**Drag & Drop:**
- Reordenação via `vuedraggable`
- Ao soltar, envia `POST /audio/reorder` com nova ordem

**Endpoints:**
- `POST /audio/setaudio` — upload de áudio (autenticado)
- `GET /audio/getplaylist` — lista playlist do usuário (autenticado)
- `POST /audio/delete` — deleta áudio e arquivo (autenticado)
- `POST /audio/reorder` — reordena playlist (autenticado)

---

### 5. Analytics

**Controller:** `AnalyticController.php`

**Actions:** `GetHeatmap`, `GetFocusDist`, `GetTodayFocus`

**Componentes:** `Heatmap.vue`, `DistGraph.vue`

#### Heatmap
- Exibe os últimos 100 dias com sessões `completed` completadas
- Formato visual similar ao contribution graph do GitHub
- Dados: lista de datas (`YYYY-MM-DD`)

#### Gráfico de Distribuição Semanal
- Mostra minutos de foco por dia (7 colunas)
- Navegação entre semanas (anterior/próxima)
- Exibe título da semana ("02 - 08 de Jan. de 2026") e total da semana
- Calcula via `CarbonPeriod` do domingo ao sábado

#### Minutos de Foco Hoje
- Soma de `duration` de todas as sessões `completed` do dia atual
- Exibido na tela principal (`Index.vue`)

**Endpoints:**
- `GET /pomodoro/analytics` — página de analytics (autenticado)
- `POST /pomodoro/analytic/dist/prevweek` — dados da semana anterior
- `POST /pomodoro/analytic/dist/nextweek` — dados da próxima semana

---

### 6. Autenticação

**Controller:** `AuthController.php`, `Laravel/Fortify/`
**Métodos:**
- **Login padrão:** email + senha via session (`/auth/login`)
- **Login Google:** via Firebase — se usuário existe, loga; senão, cria conta automaticamente (`/auth/logingoogle`)
- **2FA:** habilitado via Laravel Fortify (TOTP + recovery codes)
**Fluxo Google:**
1. Frontend captura credenciais do Firebase (email, uid, username, photo)
2. Envia para `POST /auth/logingoogle`
3. Backend tenta login com email + uid como senha
4. Se falha (usuário novo), cria registro e loga

---

### 7. Painel Administrativo

**Controller:** `Dashboard/CardController.php`

**Página:** `admin/Dashboard.vue`

**Middleware:** `AuthAdmin` (protege rotas `/admin/*`)

**Funcionalidades:**
- Listagem de todos os cards existentes
- Criar novo card (título, raridade, upload de imagem)
- Editar card existente (alterar título, raridade, imagem)
- Deletar card (remove registro e imagem do storage)

**Endpoints:**
- `GET /admin/dashboard` — página do dashboard (admin)
- `POST /admin/card/set` — criar ou atualizar card (admin)
- `POST /admin/card/delete` — deletar card (admin)

---

## Rotas

### Públicas
| Método | Rota | Controller | Descrição |
|---|---|---|---|
| GET | `/` | redirect → `/pomodoro` | Redireciona |
| GET | `/auth/login` | `AuthController@loginForm` | Página de login |
| POST | `/auth/login` | `AuthController@login` | Autentica por email/senha |
| POST | `/auth/logingoogle` | `AuthController@loginGoogle` | Autentica via Google |

### Autenticadas
| Método | Rota | Controller | Descrição |
|---|---|---|---|
| GET | `/pomodoro` | `PomodoroController@index` | Tela principal |
| GET | `/pomodoro/analytics` | `AnalyticController@index` | Analytics |
| GET | `/pomodoro/getcatalog` | `CatalogController@getCatalog` | Lista cards |
| POST | `/pomodoro/newfocus` | `FocusSessionController@newFocus` | Salva sessão |
| POST | `/pomodoro/analytic/dist/prevweek` | `AnalyticController@distgraph` | Semana anterior |
| POST | `/pomodoro/analytic/dist/nextweek` | `AnalyticController@distgraph` | Próxima semana |
| POST | `/audio/setaudio` | `AudioController@setAudio` | Upload áudio |
| GET | `/audio/getplaylist` | `AudioController@getPlaylist` | Lista playlist |
| POST | `/audio/delete` | `AudioController@delete` | Deleta áudio |
| POST | `/audio/reorder` | `AudioController@reorder` | Reordena playlist |

### Admin (AuthAdmin)
| Método | Rota | Controller | Descrição |
|---|---|---|---|
| GET | `/admin/dashboard` | View | Dashboard admin |
| POST | `/admin/card/set` | `CardController@setCard` | Cria/edita card |
| POST | `/admin/card/delete` | `CardController@delete` | Deleta card |

---

## Enums

### FocusSessionStatus
- `completed` — sessão finalizada
- `interrupted` — sessão pausada/cancelada

### CardRarity
- `common`
- `uncommun`
- `rare`
- `epic`
- `legendary`

---

## Migrations

| Arquivo | Descrição |
|---|---|
| `0001_01_01_000000_create_users_table` | Tabela de usuários |
| `0001_01_01_000001_create_cache_table` | Cache do Laravel |
| `0001_01_01_000002_create_jobs_table` | Queue jobs |
| `2025_08_14_170933_add_two_factor_columns` | Colunas 2FA (Fortify) |
| `2026_01_30_192430_create_focus_sessions` | Sessões de foco |
| `2026_01_30_211422_create_personal_access_tokens` | Sanctum tokens |
| `2026_01_31_181733_create_cards_table` | Cards colecionáveis |
| `2026_01_31_182346_create_user_cards_table` | Relação usuário-card |
| `2026_04_04_114812_create_audio_table` | Áudios da playlist |

---

## Fluxo Principal do Usuário

```
1. Usuário acessa a app → é redirecionado para /pomodoro
2. Faz login (email/senha ou Google)
3. Vê minutos de foco do dia e o Timer em 50:00
4. Configura playlist (upload de mp3) — opcional
5. Clica em iniciar no Timer:
   - Som de partida toca
   - Playlist começa a tocar
   - Countdown inicia no frontend
6. Timer vai para 00:00:
   - Som de conclusão toca
   - Sessão salva como "completed"
   - Card aleatório é sorteado
   - Animação de desbloqueio é exibida
7. Usuário pode consultar analytics (heatmap + gráfico semanal)
8. Usuário pode ver catálogo de cards desbloqueados/bloqueados
9. Repete para colecionar mais cards
```
