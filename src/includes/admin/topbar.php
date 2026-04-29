 <style>
     .topbar {
         height: 70px;
         display: flex;
         align-items: center;
         justify-content: space-between;
         padding: 0 25px;
         background: rgba(11, 17, 33, 0.8);
         backdrop-filter: blur(10px);
         position: sticky;
         top: 0;
         z-index: 1000;
         border-bottom: 1px solid var(--glass-border);
     }
 </style>
 <nav class="topbar">
     <div class="d-flex align-items-center">
         <button class="btn text-white d-lg-none me-3 p-0" id="toggleSidebar"><i class="fa-solid fa-bars-staggered fs-4"></i></button>
         <h5 class="mb-0 fw-bold d-none d-sm-block">Dashboard</h5>
     </div>

     <div class="d-flex align-items-center">
         <div class="text-end me-3 d-none d-md-block">
             <div class="small fw-bold"><?= htmlspecialchars($_SESSION['nama_admin'] ?? 'Admin'); ?></div>
             <div class="font-12 opacity-50 text-gold"><i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i> Online</div>
         </div>
         <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold shadow-sm" style="width: 40px; height: 40px; border: 2px solid rgba(255,255,255,0.1);">
             <?= strtoupper(substr($_SESSION['nama_lengkap'] ?? 'A', 0, 1)); ?>
         </div>
     </div>
 </nav>