---
name: dkp-case3-project-setup
description: Project checklist untuk membuat Task Management dengan Laravel sesuai implementation_plan.md dan CASE 3 DKP 2026.pdf
metadata:
  type: project
---

**DKP Case 3 Task Management Implementation** – Project checklist untuk membangun aplikasi web task management dengan Laravel dari nol yang memenuhi persyaratan A.1–A.9 pada dosen assignment.

**Status: In Progress** – Menunggu implementasi dimulai

## Milestones & Tasks

### 1. Project Initialization (§1, §8)
- [ ] Buat project Laravel kosong
- [ ] Install livewire/livewire
- [ ] Install livewire/flux
- [ ] Install npm dependencies
- [ ] Setup .env (database, cache config)
- [ ] Setup vite.config.js untuk bisa diakses via port forwarding

### 2. Authentication Components (§3)
- [ ] Buat App\Livewire\Auth\Register component
- [ ] Buat App\Livewire\Auth\Login component
- [ ] Setup routes untuk auth
- [ ] Setup middleware (guest, auth)

### 3. Database Setup (§4)
- [ ] Buat migration untuk tasks table (soft delete)
- [ ] Generate migrations cache dan sessions
- [ ] Buat seeder untuk users (demo account)
- [ ] Buat seeder untuk tasks (±25 data dummy)
- [ ] Jalankan migrate:fresh --seed

### 4. CRUD Features (§5)
- [ ] Setup Task model dengan SoftDeletes
- [ ] Buat policy untuk Task (batas per user)
- [ ] Buat TaskIndex Livewire component (list + filter + pagination + tab trash)
- [ ] Buat TaskForm Livewire component (create + update in modal)
- [ ] Setup routes untuk tasks
- [ ] Implementasi restore dan force delete

### 5. UI Implementation (§6)
- [ ] Setup layouts/app.blade.php (dark mode, nav bar)
- [ ] Setup layouts/guest.blade.php
- [ ] Implementasi dashboard page dengan stats
- [ ] Setup komponen Flux (button, input, textarea, select, modal, badge, card, dropdown, table)
- [ ] Buat TaskIndex view (table + filters)
- [ ] Buat TaskForm view (modal)
- [ ] Implementasi Alpine.js interaksions (mobile sidebar, dropdowns, notifications, modals)

### 6. Caching (§7)
- [ ] Implement caching untuk dashboard stats
- [ ] Setup Cache::remember() dengan driver database
- [ ] Buat tombol refresh cache manual
- [ ] Tampilkan info cache last updated

### 7. Testing & Verification (Verification Plan)
- [ ] Database check (migration + seed success)
- [ ] Auth testing (register + login)
- [ ] CRUD testing (create + read + update + soft delete + restore + force delete)
- [ ] Frontend responsiveness (360px, 768px, 1280px)
- [ ] Port forwarding testing (akses dari perangkat lain)
- [ ] Cache effect verification (60s TTL + manual refresh)

**Note**: A.10 (Jobs Queue Schedules), B.11 (Octane), dan B.12 (Redis) di-skip sesuai keputusan di implementation_plan.md