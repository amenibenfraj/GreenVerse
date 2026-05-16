<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
$admin_nom = htmlspecialchars($_SESSION['user_nom'] ?? 'Admin');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GreenVerse — Administration</title>
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════════
   PALETTE — jardin clair, naturel, aéré
═══════════════════════════════════════════════ */
:root {
  --bg:        #f5f7f0;
  --bg2:       #edf0e6;
  --bg3:       #e4e9d8;
  --white:     #ffffff;
  --card:      #ffffff;
  --border:    #d4ddc4;
  --border2:   #b8c8a0;

  --green:     #4a8c2a;
  --green2:    #5ea832;
  --green3:    #72c240;
  --green-lt:  #e8f4df;
  --green-mid: #c8e4b0;

  --sage:      #7a9e6a;
  --sage-lt:   #eef4e8;

  --brown:     #8a6040;
  --brown-lt:  #f5ede4;

  --gold:      #c8860a;
  --gold-lt:   #fef3dc;

  --text:      #2a3820;
  --text2:     #4a6035;
  --text3:     #7a9060;
  --text4:     #a8be90;

  --red:       #c04040;
  --red-lt:    #fdeaea;
  --orange:    #c86020;
  --orange-lt: #fef0e4;
  --blue:      #2878a8;
  --blue-lt:   #e4f0f8;

  --radius:    12px;
  --radius2:   18px;
  --shadow:    0 2px 16px rgba(60,80,30,0.10);
  --shadow2:   0 4px 32px rgba(60,80,30,0.14);
}

*{margin:0;padding:0;box-sizing:border-box;}

::-webkit-scrollbar{width:6px;height:6px;}
::-webkit-scrollbar-track{background:var(--bg2);}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:10px;}
::-webkit-scrollbar-thumb:hover{background:var(--sage);}

body{
  font-family:'Inter',sans-serif;
  background:var(--bg);
  color:var(--text);
  min-height:100vh;
  display:flex;
  flex-direction:column;
}

/* ═══ NAVBAR ═══ */
.topnav{
  width:100%;
  height:60px;
  background:var(--white);
  border-bottom:1px solid var(--border);
  display:flex;align-items:center;
  padding:0 24px;
  gap:16px;
  position:sticky;top:0;z-index:200;
  box-shadow:0 1px 8px rgba(60,80,30,0.07);
}
.topnav-logo{
  font-family:'Lora',serif;
  font-size:18px;font-weight:700;
  color:var(--green);
  display:flex;align-items:center;gap:8px;
  text-decoration:none;
}
.topnav-logo .leaf{font-size:20px;}
.topnav-divider{width:1px;height:28px;background:var(--border);margin:0 4px;}
.topnav-label{
  font-size:11px;font-weight:600;
  color:var(--text3);
  letter-spacing:2px;text-transform:uppercase;
}
.topnav-spacer{flex:1;}
.topnav-links{display:flex;align-items:center;gap:4px;}
.topnav-link{
  padding:6px 12px;border-radius:8px;
  font-size:13px;font-weight:500;color:var(--text2);
  text-decoration:none;cursor:pointer;
  transition:all 0.18s;border:1px solid transparent;
  display:flex;align-items:center;gap:6px;
  background:none;font-family:'Inter',sans-serif;
}
.topnav-link:hover{background:var(--green-lt);color:var(--green);border-color:var(--green-mid);}
.topnav-link.active{background:var(--green-lt);color:var(--green);border-color:var(--border2);}
.topnav-badge{
  background:var(--red);color:#fff;
  font-size:9px;font-weight:700;
  padding:2px 5px;border-radius:8px;min-width:16px;text-align:center;
}
.topnav-admin{
  display:flex;align-items:center;gap:10px;
  padding:6px 14px;
  background:var(--sage-lt);border:1px solid var(--border2);
  border-radius:20px;margin-left:8px;
}
.topnav-admin-avatar{
  width:28px;height:28px;
  background:linear-gradient(135deg,var(--green),var(--green3));
  border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:12px;font-weight:700;color:#fff;
}
.topnav-admin-name{font-size:12px;font-weight:600;color:var(--text2);}
.topnav-admin-role{font-size:10px;color:var(--text3);}

/* ═══ LAYOUT ═══ */
.layout{display:flex;flex:1;min-height:calc(100vh - 60px);}

/* ═══ SIDEBAR ═══ */
.sidebar{
  width:220px;
  background:var(--white);
  border-right:1px solid var(--border);
  padding:20px 12px;
  display:flex;flex-direction:column;gap:2px;
  position:sticky;top:60px;
  height:calc(100vh - 60px);
  overflow-y:auto;
}

.nav-section{
  font-size:9px;font-weight:700;
  color:var(--text4);
  letter-spacing:2.5px;text-transform:uppercase;
  padding:14px 10px 6px;
}

.nav-item{
  display:flex;align-items:center;gap:10px;
  padding:9px 12px;
  border-radius:9px;
  cursor:pointer;
  transition:all 0.18s;
  font-size:13px;font-weight:500;
  color:var(--text2);
  border:1px solid transparent;
  text-decoration:none;
}
.nav-item:hover{background:var(--green-lt);color:var(--green);}
.nav-item.active{
  background:var(--green-lt);color:var(--green);
  border-color:var(--green-mid);font-weight:600;
}
.nav-item.active .nav-dot{background:var(--green);}
.nav-dot{
  width:6px;height:6px;border-radius:50%;
  background:var(--border2);flex-shrink:0;
  transition:background 0.18s;
}
.nav-icon{font-size:15px;width:22px;text-align:center;flex-shrink:0;}
.nav-badge{
  margin-left:auto;background:var(--red);color:#fff;
  font-size:9px;font-weight:700;
  padding:2px 6px;border-radius:8px;min-width:18px;text-align:center;
}

.sidebar-footer-note{
  margin-top:auto;padding-top:20px;
  border-top:1px solid var(--border);
  font-size:11px;color:var(--text4);
  line-height:1.6;padding-left:4px;
}

/* ═══ MAIN CONTENT ═══ */
.main{
  flex:1;
  padding:28px 30px;
  overflow-y:auto;
  background:var(--bg);
}

.page{display:none;}
.page.active{display:block;animation:fadeUp 0.3s ease;}
@keyframes fadeUp{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:none;}}

/* ═══ PAGE HEADER ═══ */
.page-header{
  display:flex;align-items:flex-start;justify-content:space-between;
  margin-bottom:24px;padding-bottom:20px;
  border-bottom:1px solid var(--border);
}
.page-title{
  font-family:'Lora',serif;
  font-size:26px;font-weight:700;
  color:var(--text);line-height:1;margin-bottom:5px;
}
.page-subtitle{font-size:12px;color:var(--text3);}

/* ═══ STAT CARDS ═══ */
.stats-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(165px,1fr));
  gap:14px;margin-bottom:24px;
}

.stat-card{
  background:var(--card);
  border:1px solid var(--border);
  border-radius:var(--radius2);
  padding:18px 20px;
  position:relative;overflow:hidden;
  transition:all 0.2s;
  box-shadow:var(--shadow);
}
.stat-card:hover{transform:translateY(-2px);box-shadow:var(--shadow2);border-color:var(--border2);}

.stat-stripe{
  position:absolute;top:0;left:0;right:0;height:3px;
  border-radius:var(--radius2) var(--radius2) 0 0;
}
.stat-card.green .stat-stripe{background:linear-gradient(90deg,var(--green),var(--green3));}
.stat-card.gold  .stat-stripe{background:linear-gradient(90deg,var(--gold),#e8a820);}
.stat-card.red   .stat-stripe{background:linear-gradient(90deg,var(--red),#e06060);}
.stat-card.blue  .stat-stripe{background:linear-gradient(90deg,var(--blue),#4898d0);}
.stat-card.brown .stat-stripe{background:linear-gradient(90deg,var(--brown),#b08060);}

.stat-icon-pill{
  display:inline-flex;align-items:center;justify-content:center;
  width:36px;height:36px;border-radius:10px;
  font-size:17px;margin-bottom:12px;
}
.stat-card.green .stat-icon-pill{background:var(--green-lt);}
.stat-card.gold  .stat-icon-pill{background:var(--gold-lt);}
.stat-card.red   .stat-icon-pill{background:var(--red-lt);}
.stat-card.blue  .stat-icon-pill{background:var(--blue-lt);}
.stat-card.brown .stat-icon-pill{background:var(--brown-lt);}

.stat-value{
  font-family:'Lora',serif;
  font-size:26px;font-weight:700;line-height:1;margin-bottom:4px;
}
.stat-card.green .stat-value{color:var(--green);}
.stat-card.gold  .stat-value{color:var(--gold);}
.stat-card.red   .stat-value{color:var(--red);}
.stat-card.blue  .stat-value{color:var(--blue);}
.stat-card.brown .stat-value{color:var(--brown);}
.stat-label{font-size:11px;color:var(--text3);font-weight:500;}

/* ═══ CHARTS ═══ */
.charts-row{
  display:grid;grid-template-columns:1fr 1fr;
  gap:16px;margin-bottom:24px;
}
.chart-card{
  background:var(--card);
  border:1px solid var(--border);
  border-radius:var(--radius2);
  padding:22px;
  box-shadow:var(--shadow);
}
.chart-title{
  font-size:13px;font-weight:600;color:var(--text);
  margin-bottom:18px;
  display:flex;align-items:center;gap:8px;
}
.chart-title-tag{
  font-size:10px;font-weight:600;color:var(--text3);
  background:var(--bg2);border:1px solid var(--border);
  padding:2px 8px;border-radius:6px;margin-left:auto;
}

/* Bar chart */
.bar-chart{display:flex;align-items:flex-end;gap:6px;height:100px;}
.bar-wrap{flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;height:100%;justify-content:flex-end;}
.bar{
  width:100%;
  background:linear-gradient(to top,var(--green),var(--green3));
  border-radius:5px 5px 0 0;min-height:4px;
  transition:height 0.7s cubic-bezier(.34,1.56,.64,1);
  position:relative;cursor:pointer;
}
.bar:hover{filter:brightness(1.08);}
.bar::after{
  content:attr(data-val);
  position:absolute;top:-26px;left:50%;transform:translateX(-50%);
  background:var(--text);color:#fff;
  padding:3px 7px;border-radius:5px;
  font-size:10px;white-space:nowrap;
  opacity:0;transition:opacity 0.2s;pointer-events:none;
}
.bar:hover::after{opacity:1;}
.bar.gold-bar{background:linear-gradient(to top,var(--gold),#e8a820);}
.bar-label{font-size:9px;color:var(--text4);text-align:center;font-weight:500;}

/* Donut */
.donut-wrap{display:flex;align-items:center;gap:18px;}
.donut-legend{display:flex;flex-direction:column;gap:6px;flex:1;}
.legend-item{display:flex;align-items:center;gap:8px;font-size:11px;color:var(--text2);}
.legend-dot{width:8px;height:8px;border-radius:2px;flex-shrink:0;}
.legend-count{margin-left:auto;font-weight:600;color:var(--text);font-size:12px;}

/* Top plantes */
.top-plantes{display:flex;flex-direction:column;gap:10px;}
.top-item{display:flex;align-items:center;gap:10px;}
.top-rank{
  font-family:'Lora',serif;font-size:15px;font-weight:700;
  color:var(--text4);width:20px;text-align:center;flex-shrink:0;
}
.top-rank.r1{color:var(--gold);}
.top-rank.r2{color:var(--text3);}
.top-rank.r3{color:var(--brown);}
.top-bar-wrap{flex:1;}
.top-name{font-size:12px;color:var(--text);margin-bottom:4px;font-weight:500;}
.top-bar-bg{background:var(--bg3);border-radius:4px;height:5px;overflow:hidden;}
.top-bar-fill{height:100%;background:linear-gradient(90deg,var(--green),var(--green3));border-radius:4px;transition:width 0.8s cubic-bezier(.34,1.56,.64,1);}
.top-count{font-size:12px;font-weight:600;color:var(--green);min-width:28px;text-align:right;}

/* ═══ TABLE CARD ═══ */
.table-card{
  background:var(--card);
  border:1px solid var(--border);
  border-radius:var(--radius2);
  overflow:hidden;
  box-shadow:var(--shadow);
}
.table-header{
  display:flex;align-items:center;justify-content:space-between;
  padding:16px 20px;
  border-bottom:1px solid var(--border);
  gap:10px;flex-wrap:wrap;
  background:var(--bg);
}
.table-header-title{font-size:13px;font-weight:600;color:var(--text);}

.search-input{
  background:var(--white);
  border:1px solid var(--border);
  border-radius:8px;
  padding:7px 12px;
  color:var(--text);
  font-family:'Inter',sans-serif;font-size:12px;
  outline:none;transition:all 0.18s;min-width:180px;
}
.search-input:focus{border-color:var(--green);box-shadow:0 0 0 3px var(--green-lt);}
.search-input::placeholder{color:var(--text4);}
select.search-input{cursor:pointer;}

.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;font-size:12.5px;}
thead th{
  padding:11px 16px;text-align:left;
  font-size:10px;font-weight:700;color:var(--text3);
  text-transform:uppercase;letter-spacing:1px;
  border-bottom:1px solid var(--border);white-space:nowrap;
  background:var(--bg);
}
tbody tr{border-bottom:1px solid var(--border);transition:background 0.12s;}
tbody tr:hover{background:var(--sage-lt);}
tbody td{padding:12px 16px;color:var(--text2);vertical-align:middle;}
tbody tr:last-child{border-bottom:none;}

/* ═══ BADGES ═══ */
.badge{
  display:inline-flex;align-items:center;
  padding:3px 9px;border-radius:6px;
  font-size:10px;font-weight:600;
  text-transform:uppercase;letter-spacing:0.5px;
}
.badge-green {background:var(--green-lt);color:var(--green);border:1px solid var(--green-mid);}
.badge-orange{background:var(--orange-lt);color:var(--orange);border:1px solid #f0d0b8;}
.badge-red   {background:var(--red-lt);color:var(--red);border:1px solid #f0c0c0;}
.badge-blue  {background:var(--blue-lt);color:var(--blue);border:1px solid #b8d8f0;}
.badge-grey  {background:var(--bg2);color:var(--text3);border:1px solid var(--border);}
.badge-brown {background:var(--brown-lt);color:var(--brown);border:1px solid #e0ccc0;}

/* ═══ STATUT SELECT INLINE ═══ */
.statut-select{
  appearance:none;
  -webkit-appearance:none;
  background:var(--bg2) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%237a9060'/%3E%3C/svg%3E") no-repeat right 8px center;
  border:1px solid var(--border);
  border-radius:8px;
  padding:5px 26px 5px 9px;
  font-family:'Inter',sans-serif;
  font-size:11px;
  font-weight:600;
  cursor:pointer;
  outline:none;
  transition:all 0.22s cubic-bezier(.22,1,.36,1);
  color:var(--text2);
  min-width:130px;
}
.statut-select:hover{
  border-color:var(--border2);
  box-shadow:0 2px 8px rgba(60,80,30,0.12);
  transform:translateY(-1px);
}
.statut-select:focus{
  border-color:var(--green);
  box-shadow:0 0 0 3px var(--green-lt);
}

/* Couleurs par statut */
.statut-select.s-en_attente{
  background-color:var(--orange-lt);
  border-color:#f0c890;
  color:var(--orange);
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23c86020'/%3E%3C/svg%3E");
}
.statut-select.s-confirmee{
  background-color:var(--blue-lt);
  border-color:#90c4e8;
  color:var(--blue);
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%232878a8'/%3E%3C/svg%3E");
}
.statut-select.s-expediee{
  background-color:#eef4fb;
  border-color:#7ab0d8;
  color:#1a6090;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%231a6090'/%3E%3C/svg%3E");
}
.statut-select.s-livree{
  background-color:var(--green-lt);
  border-color:var(--green-mid);
  color:var(--green);
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%234a8c2a'/%3E%3C/svg%3E");
}
.statut-select.s-annulee{
  background-color:var(--red-lt);
  border-color:#f0b0b0;
  color:var(--red);
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23c04040'/%3E%3C/svg%3E");
}

/* Animation flash quand statut change */
@keyframes flashUpdate {
  0%   { transform: scale(1); }
  30%  { transform: scale(1.06); box-shadow: 0 0 0 4px rgba(74,140,42,0.25); }
  100% { transform: scale(1); }
}
.statut-select.updated {
  animation: flashUpdate 0.45s cubic-bezier(.22,1,.36,1);
}

/* Loading spinner dans le select */
.statut-select.loading {
  opacity: 0.6;
  pointer-events: none;
  cursor: wait;
}

/* ═══ BUTTONS ═══ */
.action-btns{display:flex;gap:5px;}
.btn{
  padding:7px 14px;border-radius:8px;
  font-family:'Inter',sans-serif;font-size:12px;font-weight:600;
  cursor:pointer;border:1px solid transparent;
  transition:all 0.18s;
  display:inline-flex;align-items:center;gap:5px;line-height:1;
}
.btn-primary{
  background:var(--green);color:#fff;border-color:var(--green);
  box-shadow:0 2px 8px rgba(74,140,42,0.25);
}
.btn-primary:hover{background:var(--green2);border-color:var(--green2);box-shadow:0 4px 14px rgba(74,140,42,0.35);transform:translateY(-1px);}
.btn-outline{background:var(--white);color:var(--text2);border-color:var(--border);}
.btn-outline:hover{border-color:var(--green);color:var(--green);background:var(--green-lt);}
.btn-danger{background:var(--white);color:var(--red);border-color:#f0c0c0;}
.btn-danger:hover{background:var(--red-lt);border-color:var(--red);}
.btn-sm{padding:5px 9px;font-size:11px;border-radius:6px;}

/* ═══ MODAL ═══ */
.modal-overlay{
  display:none;position:fixed;inset:0;
  background:rgba(30,40,20,0.45);
  backdrop-filter:blur(5px);
  z-index:1000;align-items:center;justify-content:center;
}
.modal-overlay.open{display:flex;}
.modal{
  background:var(--white);
  border:1px solid var(--border);
  border-radius:20px;
  width:570px;max-width:95vw;max-height:90vh;
  overflow-y:auto;
  box-shadow:var(--shadow2);
  animation:modalIn 0.28s cubic-bezier(.22,1,.36,1);
}
@keyframes modalIn{from{opacity:0;transform:scale(0.94) translateY(20px);}to{opacity:1;transform:none;}}

.modal-header{
  display:flex;align-items:center;justify-content:space-between;
  padding:20px 22px 16px;
  border-bottom:1px solid var(--border);
  background:var(--bg);
}
.modal-title{font-family:'Lora',serif;font-size:20px;font-weight:700;color:var(--text);}
.modal-close{
  background:var(--bg2);border:1px solid var(--border);color:var(--text3);
  font-size:14px;cursor:pointer;
  padding:0;border-radius:7px;line-height:1;
  transition:all 0.18s;width:28px;height:28px;
  display:flex;align-items:center;justify-content:center;
}
.modal-close:hover{background:var(--red-lt);border-color:var(--red);color:var(--red);}
.modal-body{padding:22px;}
.modal-footer{
  padding:14px 22px;border-top:1px solid var(--border);
  display:flex;gap:8px;justify-content:flex-end;
  background:var(--bg);
}

.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:13px;}
.form-group{display:flex;flex-direction:column;gap:5px;}
.form-group.full{grid-column:1/-1;}
.form-label{font-size:10px;font-weight:700;color:var(--text3);letter-spacing:1px;text-transform:uppercase;}
.form-control{
  background:var(--bg);border:1px solid var(--border);
  border-radius:8px;padding:9px 12px;
  color:var(--text);font-family:'Inter',sans-serif;font-size:13px;
  outline:none;transition:all 0.18s;
}
.form-control:focus{border-color:var(--green);box-shadow:0 0 0 3px var(--green-lt);background:var(--white);}
.form-control::placeholder{color:var(--text4);}
select.form-control{cursor:pointer;}

.form-divider{
  grid-column:1/-1;
  display:flex;align-items:center;gap:10px;
  font-size:10px;font-weight:700;color:var(--text4);
  letter-spacing:1.5px;text-transform:uppercase;
  padding:6px 0;
}
.form-divider::before,.form-divider::after{content:'';flex:1;height:1px;background:var(--border);}

/* ═══ PANIERS ═══ */
.panier-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px;}
.panier-card{
  background:var(--card);border:1px solid var(--border);
  border-radius:var(--radius2);padding:18px;
  transition:all 0.2s;box-shadow:var(--shadow);
  position:relative;overflow:hidden;
}
.panier-card:hover{border-color:var(--green);box-shadow:var(--shadow2);transform:translateY(-2px);}
.panier-card::before{
  content:'🛒';position:absolute;
  right:-8px;bottom:-8px;
  font-size:50px;opacity:0.05;
}
.panier-num{font-size:10px;font-weight:700;color:var(--text4);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:6px;}
.panier-session{font-size:10px;color:var(--text3);font-family:monospace;margin-bottom:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.panier-articles{font-size:12.5px;color:var(--text2);margin-bottom:14px;line-height:1.6;}
.panier-footer{display:flex;justify-content:space-between;align-items:flex-end;border-top:1px solid var(--border);padding-top:12px;}
.panier-total{font-family:'Lora',serif;font-size:22px;font-weight:700;color:var(--green);}
.panier-meta{font-size:10px;color:var(--text3);text-align:right;line-height:1.7;}
.panier-count-badge{
  display:inline-block;background:var(--green-lt);color:var(--green);
  border:1px solid var(--green-mid);
  font-size:10px;font-weight:700;
  padding:2px 7px;border-radius:6px;margin-bottom:8px;
}

/* ═══ ITEMS ═══ */
.items-list{display:flex;flex-direction:column;gap:6px;margin-top:10px;}
.item-row{
  display:flex;justify-content:space-between;align-items:center;
  background:var(--bg);border:1px solid var(--border);
  border-radius:8px;padding:8px 12px;font-size:12px;
  transition:background 0.12s;
}
.item-row:hover{background:var(--green-lt);}
.item-name{color:var(--text);font-weight:500;}
.item-qty{color:var(--text3);background:var(--bg2);border-radius:5px;padding:1px 7px;font-size:11px;}
.item-price{color:var(--green);font-weight:600;}

/* ═══ DETAIL VIEW ═══ */
.detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;}
.detail-item{background:var(--bg);border:1px solid var(--border);border-radius:10px;padding:11px 14px;}
.detail-item.span2{grid-column:1/-1;}
.detail-item.highlight{background:var(--green-lt);border-color:var(--green-mid);}
.detail-label{font-size:9px;font-weight:700;color:var(--text4);letter-spacing:1.2px;text-transform:uppercase;margin-bottom:4px;}
.detail-value{font-size:13px;color:var(--text);font-weight:500;}
.detail-item.highlight .detail-value{font-family:'Lora',serif;font-size:22px;color:var(--green);font-weight:700;}

/* ═══ SECTION LABEL ═══ */
.section-label{
  font-size:10px;font-weight:700;color:var(--text3);
  letter-spacing:1.5px;text-transform:uppercase;
  margin-bottom:10px;margin-top:4px;
  display:flex;align-items:center;gap:8px;
}
.section-label::after{content:'';flex:1;height:1px;background:var(--border);}

/* ═══ LOADING ═══ */
.loading{
  display:flex;align-items:center;justify-content:center;
  padding:50px;color:var(--text3);flex-direction:column;gap:12px;
}
.spinner{
  width:28px;height:28px;
  border:2px solid var(--border);
  border-top-color:var(--green);
  border-radius:50%;
  animation:spin 0.7s linear infinite;
}
@keyframes spin{to{transform:rotate(360deg);}}

.empty{text-align:center;padding:40px;color:var(--text4);font-size:13px;}

/* ═══ TOAST ═══ */
#toast{
  position:fixed;bottom:22px;right:22px;
  background:var(--white);border:1px solid var(--border);
  padding:11px 18px;border-radius:10px;
  font-size:12px;font-weight:500;color:var(--text);
  box-shadow:var(--shadow2);
  transform:translateY(70px);opacity:0;
  transition:all 0.3s cubic-bezier(.22,1,.36,1);z-index:9999;
  display:flex;align-items:center;gap:8px;
}
#toast.show{transform:translateY(0);opacity:1;}
#toast.success{border-color:var(--green);color:var(--green);}
#toast.error{border-color:var(--red);color:var(--red);}

/* ═══ RESPONSIVE ═══ */
@media(max-width:960px){
  .sidebar{width:52px;padding:12px 6px;}
  .nav-item span,.nav-badge,.nav-section,.sidebar-footer-note{display:none;}
  .nav-icon{width:100%;text-align:center;}
  .main{padding:18px 14px;}
  .charts-row{grid-template-columns:1fr;}
  .topnav-admin-name,.topnav-admin-role{display:none;}
}
@media(max-width:680px){
  .stats-grid{grid-template-columns:repeat(2,1fr);}
  .topnav-links{display:none;}
  .statut-select{min-width:110px;font-size:10px;}
}
</style>
</head>
<body>

<!-- ══════════════════════════════════
     NAVBAR
══════════════════════════════════ -->
<?php include 'navbar.php'; ?>
<nav class="topnav">
  <a href="index.php" class="topnav-logo">
    <span class="leaf">🌿</span> GreenVerse
  </a>
  <div class="topnav-divider"></div>
  <span class="topnav-label">Admin</span>
  <div class="topnav-spacer"></div>
  <div class="topnav-links">
    <span class="topnav-link active" onclick="navigate('dashboard',this)">📊 Dashboard</span>
    <span class="topnav-link" onclick="navigate('commandes',this)">
      📦 Commandes <span class="topnav-badge" id="topnav-badge">—</span>
    </span>
    <span class="topnav-link" onclick="navigate('paniers',this)">🛒 Paniers</span>
    <span class="topnav-link" onclick="navigate('plantes',this)">🌱 Plantes</span>
    <span class="topnav-link" onclick="navigate('produits',this)">🪴 Produits</span>
    <span class="topnav-link" onclick="navigate('users',this)">👤 Membres</span>
  </div>
  <div class="topnav-admin">
    <div class="topnav-admin-avatar"><?= strtoupper(substr($admin_nom,0,1)) ?></div>
    <div>
      <div class="topnav-admin-name"><?= $admin_nom ?></div>
      <div class="topnav-admin-role">Administrateur</div>
    </div>
  </div>
</nav>

<!-- ══════════════════════════════════
     LAYOUT
══════════════════════════════════ -->
<div class="layout">

<!-- ── SIDEBAR ── -->
<aside class="sidebar">
  <div class="nav-section">Principal</div>
  <div class="nav-item active" onclick="navigate('dashboard',this)">
    <span class="nav-icon">📊</span><span>Dashboard</span>
  </div>

  <div class="nav-section">Boutique</div>
  <div class="nav-item" onclick="navigate('commandes',this)">
    <span class="nav-icon">📦</span><span>Commandes</span>
    <span class="nav-badge" id="badge-commandes">—</span>
  </div>
  <div class="nav-item" onclick="navigate('paniers',this)">
    <span class="nav-icon">🛒</span><span>Paniers actifs</span>
  </div>
  <div class="nav-item" onclick="navigate('plantes',this)">
    <span class="nav-icon">🌱</span><span>Plantes</span>
  </div>
  <div class="nav-item" onclick="navigate('produits',this)">
    <span class="nav-icon">🪴</span><span>Produits</span>
  </div>

  <div class="nav-section">Membres</div>
  <div class="nav-item" onclick="navigate('users',this)">
    <span class="nav-icon">👤</span><span>Utilisateurs</span>
  </div>

  <div class="sidebar-footer-note">
    Connecté en tant que<br><strong style="color:var(--text2)"><?= $admin_nom ?></strong>
  </div>
</aside>

<!-- ══════════════════════════════════
     MAIN
══════════════════════════════════ -->
<main class="main">

  <!-- ── DASHBOARD ── -->
  <div class="page active" id="page-dashboard">
    <div class="page-header">
      <div>
        <div class="page-title">🌿 Tableau de bord</div>
        <div class="page-subtitle">Aperçu général de la boutique GreenVerse</div>
      </div>
      <button class="btn btn-outline" onclick="loadStats()">↻ Actualiser</button>
    </div>

    <div class="stats-grid" id="stats-grid">
      <div class="loading" style="grid-column:1/-1"><div class="spinner"></div></div>
    </div>

    <div class="charts-row">
      <div class="chart-card">
        <div class="chart-title">
          📈 Commandes — 6 derniers mois
          <span class="chart-title-tag">Mensuel</span>
        </div>
        <div class="bar-chart" id="chart-commandes"></div>
      </div>
      <div class="chart-card">
        <div class="chart-title">
          ⬤ Répartition des statuts
          <span class="chart-title-tag">Total</span>
        </div>
        <div class="donut-wrap">
          <canvas id="chart-statuts" width="110" height="110"></canvas>
          <div class="donut-legend" id="legend-statuts"></div>
        </div>
      </div>
    </div>

    <div class="charts-row">
      <div class="chart-card">
        <div class="chart-title">🏆 Top 5 plantes vendues</div>
        <div class="top-plantes" id="top-plantes"></div>
      </div>
      <div class="chart-card">
        <div class="chart-title">
          🗂️ Plantes par catégorie
          <span class="chart-title-tag">Stock</span>
        </div>
        <div class="bar-chart" id="chart-categories"></div>
      </div>
    </div>
  </div>

  <!-- ── COMMANDES ── -->
  <div class="page" id="page-commandes">
    <div class="page-header">
      <div>
        <div class="page-title">📦 Commandes</div>
        <div class="page-subtitle">Gestion complète — Créer · Consulter · Modifier · Supprimer · Changer statut</div>
      </div>
      <button class="btn btn-primary" onclick="openAddModal()">＋ Nouvelle commande</button>
    </div>
    <div class="table-card">
      <div class="table-header">
        <span class="table-header-title">Toutes les commandes</span>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <input class="search-input" type="text" id="search-commandes" placeholder="🔍 Nom, email, ID…" oninput="filterCommandes()">
          <select class="search-input" id="filter-statut" onchange="filterCommandes()">
            <option value="">Tous les statuts</option>
            <option value="en_attente">⏳ En attente</option>
            <option value="confirmee">✅ Confirmée</option>
            <option value="expediee">📫 Expédiée</option>
            <option value="livree">📬 Livrée</option>
            <option value="annulee">❌ Annulée</option>
          </select>
        </div>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Client</th>
              <th>Email</th>
              <th>Total TTC</th>
              <th>Articles</th>
              <th>Statut</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="tbody-commandes">
            <tr><td colspan="8"><div class="loading"><div class="spinner"></div></div></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ── PANIERS ── -->
  <div class="page" id="page-paniers">
    <div class="page-header">
      <div>
        <div class="page-title">🛒 Paniers actifs</div>
        <div class="page-subtitle">Sessions d'achat en cours des visiteurs</div>
      </div>
      <button class="btn btn-outline" onclick="loadPaniers()">↻ Actualiser</button>
    </div>
    <div class="panier-grid" id="panier-grid">
      <div class="loading" style="grid-column:1/-1"><div class="spinner"></div></div>
    </div>
  </div>

  <!-- ── PLANTES ── -->
  <div class="page" id="page-plantes">
    <div class="page-header">
      <div>
        <div class="page-title">🌱 Plantes</div>
        <div class="page-subtitle">Catalogue complet des plantes disponibles</div>
      </div>
    </div>
    <div class="table-card">
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>Nom</th><th>Catégorie</th><th>Prix</th><th>Stock</th><th>État</th></tr></thead>
          <tbody id="tbody-plantes">
            <tr><td colspan="6"><div class="loading"><div class="spinner"></div></div></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ── PRODUITS ── -->
  <div class="page" id="page-produits">
    <div class="page-header">
      <div>
        <div class="page-title">🪴 Produits</div>
        <div class="page-subtitle">Accessoires et articles de jardinage</div>
      </div>
    </div>
    <div class="table-card">
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>Nom</th><th>Catégorie</th><th>Prix</th><th>Stock</th><th>État</th></tr></thead>
          <tbody id="tbody-produits">
            <tr><td colspan="6"><div class="loading"><div class="spinner"></div></div></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ── USERS ── -->
  <div class="page" id="page-users">
    <div class="page-header">
      <div>
        <div class="page-title">👤 Utilisateurs</div>
        <div class="page-subtitle">Membres de la communauté GreenVerse</div>
      </div>
    </div>
    <div class="table-card">
      <div class="table-header">
        <span class="table-header-title">Tous les membres</span>
        <input class="search-input" type="text" id="search-users" placeholder="🔍 Nom, email…" oninput="searchUsers()">
      </div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>Nom</th><th>Email</th><th>Commandes</th><th>Total dépensé</th><th>Inscrit le</th></tr></thead>
          <tbody id="tbody-users">
            <tr><td colspan="6"><div class="loading"><div class="spinner"></div></div></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</main>
</div><!-- end .layout -->

<!-- ══════════════════════════════════
     MODAL — COMMANDE (Créer / Modifier)
══════════════════════════════════ -->
<div class="modal-overlay" id="modal-commande">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="modal-title">Commande</div>
      <button class="modal-close" onclick="closeModal()">✕</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="f-id">
      <div class="form-grid">
        <div class="form-divider">Informations client</div>
        <div class="form-group">
          <label class="form-label">Prénom</label>
          <input class="form-control" id="f-prenom" placeholder="Jean">
        </div>
        <div class="form-group">
          <label class="form-label">Nom</label>
          <input class="form-control" id="f-nom" placeholder="Dupont">
        </div>
        <div class="form-group full">
          <label class="form-label">Email</label>
          <input class="form-control" id="f-email" type="email" placeholder="jean@exemple.fr">
        </div>
        <div class="form-group">
          <label class="form-label">Téléphone</label>
          <input class="form-control" id="f-telephone" placeholder="06 00 00 00 00">
        </div>
        <div class="form-group">
          <label class="form-label">Mode de paiement</label>
          <select class="form-control" id="f-mode_paiement">
            <option value="carte">💳 Carte bancaire</option>
            <option value="paypal">🅿️ PayPal</option>
            <option value="virement">🏦 Virement</option>
          </select>
        </div>

        <div class="form-divider">Livraison</div>
        <div class="form-group full">
          <label class="form-label">Adresse</label>
          <input class="form-control" id="f-adresse" placeholder="12 rue des Fleurs">
        </div>
        <div class="form-group">
          <label class="form-label">Ville</label>
          <input class="form-control" id="f-ville" placeholder="Paris">
        </div>
        <div class="form-group">
          <label class="form-label">Code postal</label>
          <input class="form-control" id="f-code_postal" placeholder="75001">
        </div>

        <div class="form-divider">Commande</div>
        <div class="form-group">
          <label class="form-label">Total TTC (DT)</label>
          <input class="form-control" id="f-total_ttc" type="number" step="0.01" placeholder="0.00">
        </div>
        <div class="form-group">
          <label class="form-label">Statut</label>
          <select class="form-control" id="f-statut">
            <option value="en_attente">⏳ En attente</option>
            <option value="confirmee">✅ Confirmée</option>
            <option value="expediee">📫 Expédiée</option>
            <option value="livree">📬 Livrée</option>
            <option value="annulee">❌ Annulée</option>
          </select>
        </div>
      </div>
      <div id="modal-items-section" style="display:none;margin-top:16px;">
        <div class="section-label">Articles de la commande</div>
        <div class="items-list" id="modal-items"></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal()">Annuler</button>
      <button class="btn btn-primary" id="modal-save-btn" onclick="saveCommande()">Enregistrer</button>
    </div>
  </div>
</div>

<!-- MODAL — DETAIL -->
<div class="modal-overlay" id="modal-detail">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="modal-detail-title">Détail commande</div>
      <button class="modal-close" onclick="document.getElementById('modal-detail').classList.remove('open')">✕</button>
    </div>
    <div class="modal-body" id="modal-detail-body"></div>
  </div>
</div>

<div id="toast"></div>

<script>
const API = 'api.php';

// ─── NAVIGATION ──────────────────────────────────────────────────────────────
let currentPage = 'dashboard';

function navigate(page, el) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-item, .topnav-link').forEach(n => n.classList.remove('active'));
  document.getElementById('page-' + page).classList.add('active');
  if (el) el.classList.add('active');
  currentPage = page;
  const loaders = {
    commandes: loadCommandes,
    paniers:   loadPaniers,
    plantes:   loadPlantes,
    produits:  loadProduits,
    users:     loadUsers
  };
  if (loaders[page]) loaders[page]();
}

// ─── TOAST ───────────────────────────────────────────────────────────────────
function toast(msg, type = 'success') {
  const el = document.getElementById('toast');
  const icon = type === 'success' ? '✓' : '✕';
  el.innerHTML = `<span>${icon}</span> ${msg}`;
  el.className = 'show ' + type;
  clearTimeout(el._t);
  el._t = setTimeout(() => el.className = '', 3200);
}

// ─── API ─────────────────────────────────────────────────────────────────────
async function api(action, method = 'GET', body = null, params = '') {
  const url = `${API}?action=${action}${params}`;
  const opts = { method, headers: { 'Content-Type': 'application/json' } };
  if (body) opts.body = JSON.stringify(body);
  try {
    const r = await fetch(url, opts);
    const data = await r.json();
    return Array.isArray(data) ? data : (data || {});
  } catch(e) {
    toast('Erreur réseau', 'error');
    return {};
  }
}

// ─── HELPERS ─────────────────────────────────────────────────────────────────
const fmt     = n => parseFloat(n||0).toFixed(2) + ' DT';
const fmtDate = d => d ? new Date(d).toLocaleDateString('fr-FR') : '—';
const fmtDT   = d => d ? new Date(d).toLocaleString('fr-FR',{dateStyle:'short',timeStyle:'short'}) : '—';

function badgeStatut(s) {
  const map = {
    en_attente: ['badge-orange','⏳ En attente'],
    pending:    ['badge-orange','⏳ En attente'],
    confirmee:  ['badge-blue','✅ Confirmée'],
    expediee:   ['badge-blue','📫 Expédiée'],
    livree:     ['badge-green','📬 Livrée'],
    annulee:    ['badge-red','❌ Annulée'],
  };
  const [cls,label] = map[s] || ['badge-grey', s||'—'];
  return `<span class="badge ${cls}">${label}</span>`;
}

function stockBadge(stock) {
  stock = parseInt(stock||0);
  if (stock === 0) return '<span class="badge badge-red">Rupture</span>';
  if (stock < 10)  return '<span class="badge badge-orange">Faible</span>';
  return '<span class="badge badge-green">OK</span>';
}

// ─── Libellés des statuts pour les toasts ────────────────────────────────────
const statutLabels = {
  en_attente: '⏳ En attente',
  confirmee:  '✅ Confirmée',
  expediee:   '📫 Expédiée',
  livree:     '📬 Livrée',
  annulee:    '❌ Annulée',
};

// ═══════════════════════════════════════════════════════════════════════════════
// STATS
// ═══════════════════════════════════════════════════════════════════════════════
async function loadStats() {
  document.getElementById('stats-grid').innerHTML = '<div class="loading" style="grid-column:1/-1"><div class="spinner"></div></div>';
  const data = await api('stats');

  const cards = [
    { icon:'📦', label:'Commandes',      val: data.total_commandes,    cls:'blue'  },
    { icon:'💰', label:'Revenus TTC',    val: fmt(data.revenus_total), cls:'gold'  },
    { icon:'👤', label:'Utilisateurs',   val: data.total_users,        cls:'green' },
    { icon:'🌱', label:'Plantes',        val: data.total_plantes,      cls:'green' },
    { icon:'🪴', label:'Produits',       val: data.total_produits,     cls:'green' },
    { icon:'⏳', label:'En attente',     val: data.commandes_attente,  cls:'brown' },
    { icon:'⚠️', label:'Stock faible',   val: data.stock_faible,       cls:'red'   },
    { icon:'🚫', label:'Rupture stock',  val: data.stock_rupture,      cls:'red'   },
    { icon:'🆕', label:'Nouveaux (30j)', val: data.nouveaux_users,     cls:'blue'  },
    { icon:'🛒', label:'Paniers actifs', val: data.paniers_actifs,     cls:'gold'  },
  ];

  document.getElementById('stats-grid').innerHTML = cards.map(c => `
    <div class="stat-card ${c.cls}">
      <div class="stat-stripe"></div>
      <div class="stat-icon-pill">${c.icon}</div>
      <div class="stat-value">${c.val}</div>
      <div class="stat-label">${c.label}</div>
    </div>`).join('');

  // Badges
  const nb = data.commandes_attente || 0;
  document.getElementById('badge-commandes').textContent = nb;
  document.getElementById('topnav-badge').textContent = nb;

  // Bar chart mois
  const mois = data.commandes_par_mois || [];
  const maxNb = Math.max(...mois.map(m => m.nb), 1);
  document.getElementById('chart-commandes').innerHTML = mois.length
    ? mois.map(m => `
        <div class="bar-wrap">
          <div class="bar" style="height:${Math.round((m.nb/maxNb)*100)}%" data-val="${m.nb} cmd · ${fmt(m.revenus)}"></div>
          <div class="bar-label">${m.mois.split(' ')[0]}</div>
        </div>`).join('')
    : '<div class="empty">Aucune donnée</div>';

  // Donut
  drawDonut(data.statuts || []);

  // Top plantes
  const top = data.top_plantes || [];
  const maxV = Math.max(...top.map(t => t.total_vendu), 1);
  const rankCls = ['r1','r2','r3'];
  document.getElementById('top-plantes').innerHTML = top.length
    ? top.map((t,i) => `
        <div class="top-item">
          <div class="top-rank ${rankCls[i]||''}">${i+1}</div>
          <div class="top-bar-wrap">
            <div class="top-name">${t.nom}</div>
            <div class="top-bar-bg"><div class="top-bar-fill" style="width:${Math.round((t.total_vendu/maxV)*100)}%"></div></div>
          </div>
          <div class="top-count">${t.total_vendu}</div>
        </div>`).join('')
    : '<div class="empty">Aucune vente enregistrée</div>';

  // Bar chart catégories
  const cats = data.stock_categories || [];
  const maxC = Math.max(...cats.map(c => c.nb_plantes), 1);
  document.getElementById('chart-categories').innerHTML = cats.length
    ? cats.map(c => `
        <div class="bar-wrap">
          <div class="bar gold-bar" style="height:${Math.round((c.nb_plantes/maxC)*100)}%" data-val="${c.nb_plantes} plantes"></div>
          <div class="bar-label">${(c.categorie||'—').substring(0,9)}</div>
        </div>`).join('')
    : '<div class="empty">Aucune catégorie</div>';
}

function drawDonut(statuts) {
  const canvas = document.getElementById('chart-statuts');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  const colors = ['#4a8c2a','#c8860a','#2878a8','#c04040','#7a9e6a','#c86020'];
  const labels = {
    en_attente:'En attente', pending:'En attente',
    confirmee:'Confirmée', expediee:'Expédiée',
    livree:'Livrée', annulee:'Annulée'
  };
  const total = statuts.reduce((s,x) => s+parseInt(x.nb),0) || 1;
  let angle = -Math.PI/2;
  const cx=55, cy=55, r=48, ri=28;
  ctx.clearRect(0,0,110,110);
  statuts.forEach((s,i) => {
    const slice = (parseInt(s.nb)/total)*Math.PI*2;
    ctx.beginPath(); ctx.moveTo(cx,cy);
    ctx.arc(cx,cy,r,angle,angle+slice);
    ctx.closePath(); ctx.fillStyle=colors[i%colors.length]; ctx.fill();
    angle += slice;
  });
  ctx.beginPath(); ctx.arc(cx,cy,ri,0,Math.PI*2);
  ctx.fillStyle = '#ffffff'; ctx.fill();
  ctx.fillStyle = '#2a3820';
  ctx.font = 'bold 14px Lora, serif';
  ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
  ctx.fillText(total, cx, cy-4);
  ctx.fillStyle = '#7a9060';
  ctx.font = '500 9px Inter, sans-serif';
  ctx.fillText('TOTAL', cx, cy+8);

  document.getElementById('legend-statuts').innerHTML = statuts.map((s,i) => `
    <div class="legend-item">
      <div class="legend-dot" style="background:${colors[i%colors.length]}"></div>
      <span>${labels[s.statut]||s.statut}</span>
      <span class="legend-count">${s.nb}</span>
    </div>`).join('');
}

// ═══════════════════════════════════════════════════════════════════════════════
// COMMANDES
// ═══════════════════════════════════════════════════════════════════════════════
let allCommandes = [];

async function loadCommandes() {
  document.getElementById('tbody-commandes').innerHTML =
    '<tr><td colspan="8"><div class="loading"><div class="spinner"></div></div></td></tr>';
  const data = await api('commandes');
  allCommandes = Array.isArray(data) ? data : [];
  renderCommandes(allCommandes);
}

function renderCommandes(list) {
  const tbody = document.getElementById('tbody-commandes');
  if (!list.length) {
    tbody.innerHTML = '<tr><td colspan="8"><div class="empty">Aucune commande</div></td></tr>';
    return;
  }
  tbody.innerHTML = list.map(c => `
    <tr id="row-${c.id}">
      <td><span style="font-family:monospace;font-size:11px;color:var(--text4)">#${c.id}</span></td>
      <td><strong style="color:var(--text)">${c.prenom||''} ${c.nom||''}</strong></td>
      <td style="color:var(--text3);font-size:12px">${c.email||'—'}</td>
      <td><strong style="color:var(--green)">${fmt(c.total_ttc)}</strong></td>
      <td><span class="badge badge-grey">${c.nb_items||0} art.</span></td>
      <td>
        <select
          class="statut-select s-${c.statut||'en_attente'}"
          id="statut-sel-${c.id}"
          onchange="changeStatut(${c.id}, this)"
          title="Changer le statut de la commande">
          <option value="en_attente" ${c.statut==='en_attente'?'selected':''}>⏳ En attente</option>
          <option value="confirmee"  ${c.statut==='confirmee' ?'selected':''}>✅ Confirmée</option>
          <option value="expediee"   ${c.statut==='expediee'  ?'selected':''}>📫 Expédiée</option>
          <option value="livree"     ${c.statut==='livree'    ?'selected':''}>📬 Livrée</option>
          <option value="annulee"    ${c.statut==='annulee'   ?'selected':''}>❌ Annulée</option>
        </select>
      </td>
      <td style="color:var(--text3);font-size:12px">${fmtDate(c.created_at)}</td>
      <td>
        <div class="action-btns">
          <button class="btn btn-outline btn-sm" onclick="viewCommande(${c.id})" title="Voir le détail">👁</button>
          <button class="btn btn-outline btn-sm" onclick="editCommande(${c.id})" title="Modifier">✏️</button>
          <button class="btn btn-danger btn-sm"  onclick="deleteCommande(${c.id})" title="Supprimer">🗑</button>
        </div>
      </td>
    </tr>`).join('');
}

function filterCommandes() {
  const s  = document.getElementById('search-commandes').value.toLowerCase();
  const st = document.getElementById('filter-statut').value;
  renderCommandes(allCommandes.filter(c => {
    const ms = !s
      || (c.nom||'').toLowerCase().includes(s)
      || (c.email||'').toLowerCase().includes(s)
      || String(c.id).includes(s);
    return ms && (!st || c.statut === st);
  }));
}

// ─── CHANGEMENT DE STATUT INLINE ─────────────────────────────────────────────
async function changeStatut(id, selectEl) {
  const nouveau    = selectEl.value;
  const precedent  = allCommandes.find(x => x.id == id)?.statut || 'en_attente';

  // 1. Feedback visuel immédiat — couleur + état loading
  selectEl.className = `statut-select s-${nouveau} loading`;

  // 2. Construire le body complet (update_commande attend tous les champs)
  const c = allCommandes.find(x => x.id == id);
  if (!c) return;

  const body = {
    id:            c.id,
    prenom:        c.prenom        || '',
    nom:           c.nom           || '',
    email:         c.email         || '',
    telephone:     c.telephone     || '',
    adresse:       c.adresse       || '',
    ville:         c.ville         || '',
    code_postal:   c.code_postal   || '',
    total_ttc:     parseFloat(c.total_ttc) || 0,
    mode_paiement: c.mode_paiement || 'carte',
    statut:        nouveau,
  };

  const res = await api('update_commande', 'POST', body);

  if (res.success) {
    // 3. Mettre à jour le cache local
    c.statut = nouveau;

    // 4. Animation flash de confirmation
    selectEl.classList.remove('loading');
    selectEl.classList.add('updated');
    setTimeout(() => selectEl.classList.remove('updated'), 500);

    // 5. Toast
    toast(`Commande #${id} → ${statutLabels[nouveau] || nouveau} ✓`);

    // 6. Recalculer le badge "En attente"
    updateAttenteBadge();

  } else {
    // 7. Rollback visuel en cas d'erreur
    selectEl.value = precedent;
    selectEl.className = `statut-select s-${precedent}`;
    toast(res.error || 'Erreur lors de la mise à jour', 'error');
  }
}

// Met à jour les badges "En attente" sidebar + topnav
function updateAttenteBadge() {
  const nb = allCommandes.filter(x => x.statut === 'en_attente').length;
  document.getElementById('badge-commandes').textContent = nb || 0;
  document.getElementById('topnav-badge').textContent    = nb || 0;
}

// ─── VOIR DÉTAIL ─────────────────────────────────────────────────────────────
function viewCommande(id) {
  const c = allCommandes.find(x => x.id==id);
  if (!c) return;
  document.getElementById('modal-detail-title').textContent = `Commande #${id}`;
  const items = c.items || [];
  document.getElementById('modal-detail-body').innerHTML = `
    <div class="detail-grid">
      <div class="detail-item">
        <div class="detail-label">Client</div>
        <div class="detail-value">${c.prenom||''} ${c.nom||''}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Email</div>
        <div class="detail-value" style="font-size:12px">${c.email||'—'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Téléphone</div>
        <div class="detail-value">${c.telephone||'—'}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Paiement</div>
        <div class="detail-value">${c.mode_paiement||'—'}</div>
      </div>
      <div class="detail-item span2">
        <div class="detail-label">Adresse</div>
        <div class="detail-value">${c.adresse||'—'}, ${c.code_postal||''} ${c.ville||''}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Statut</div>
        <div class="detail-value">${badgeStatut(c.statut)}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Total HT</div>
        <div class="detail-value">${fmt(c.total_ht)}</div>
      </div>
      <div class="detail-item highlight span2">
        <div class="detail-label">Total TTC</div>
        <div class="detail-value">${fmt(c.total_ttc)}</div>
      </div>
    </div>
    ${items.length ? `
      <div class="section-label">Articles (${items.length})</div>
      <div class="items-list">
        ${items.map(it=>`
          <div class="item-row">
            <span class="item-name">${it.nom}</span>
            <span class="item-qty">×${it.quantite}</span>
            <span class="item-price">${fmt((it.prix||0)*(it.quantite||1))}</span>
          </div>`).join('')}
      </div>` : '<div class="empty" style="padding:16px">Aucun article</div>'}
  `;
  document.getElementById('modal-detail').classList.add('open');
}

// ─── CRÉER COMMANDE ───────────────────────────────────────────────────────────
function openAddModal() {
  document.getElementById('modal-title').textContent    = 'Nouvelle commande';
  document.getElementById('modal-save-btn').textContent = 'Créer la commande';
  ['id','prenom','nom','email','telephone','adresse','ville','code_postal','total_ttc']
    .forEach(f => document.getElementById('f-'+f).value = '');
  document.getElementById('f-statut').value       = 'en_attente';
  document.getElementById('f-mode_paiement').value = 'carte';
  document.getElementById('modal-items-section').style.display = 'none';
  document.getElementById('modal-commande').classList.add('open');
}

// ─── MODIFIER COMMANDE (modal complet) ───────────────────────────────────────
function editCommande(id) {
  const c = allCommandes.find(x => x.id==id);
  if (!c) return;
  document.getElementById('modal-title').textContent    = `Modifier commande #${id}`;
  document.getElementById('modal-save-btn').textContent = 'Enregistrer';
  document.getElementById('f-id').value = c.id;
  ['prenom','nom','email','telephone','adresse','ville','code_postal','total_ttc','statut','mode_paiement']
    .forEach(f => document.getElementById('f-'+f).value = c[f]||'');
  const items = c.items || [];
  if (items.length) {
    document.getElementById('modal-items').innerHTML = items.map(it=>`
      <div class="item-row">
        <span class="item-name">${it.nom}</span>
        <span class="item-qty">×${it.quantite}</span>
        <span class="item-price">${fmt((it.prix||0)*(it.quantite||1))}</span>
      </div>`).join('');
    document.getElementById('modal-items-section').style.display = 'block';
  } else {
    document.getElementById('modal-items-section').style.display = 'none';
  }
  document.getElementById('modal-commande').classList.add('open');
}

// ─── SAUVEGARDER (créer ou modifier) ─────────────────────────────────────────
async function saveCommande() {
  const id = document.getElementById('f-id').value;
  const body = {
    id:            id ? parseInt(id) : undefined,
    prenom:        document.getElementById('f-prenom').value,
    nom:           document.getElementById('f-nom').value,
    email:         document.getElementById('f-email').value,
    telephone:     document.getElementById('f-telephone').value,
    adresse:       document.getElementById('f-adresse').value,
    ville:         document.getElementById('f-ville').value,
    code_postal:   document.getElementById('f-code_postal').value,
    total_ttc:     parseFloat(document.getElementById('f-total_ttc').value) || 0,
    statut:        document.getElementById('f-statut').value,
    mode_paiement: document.getElementById('f-mode_paiement').value,
  };
  const btn = document.getElementById('modal-save-btn');
  btn.disabled = true; btn.textContent = 'Enregistrement…';
  const res = await api(id ? 'update_commande' : 'add_commande', 'POST', body);
  btn.disabled = false; btn.textContent = id ? 'Enregistrer' : 'Créer la commande';
  if (res.success) {
    toast(id ? 'Commande mise à jour ✓' : 'Commande créée ✓');
    closeModal();
    loadCommandes();
  } else {
    toast(res.error || 'Erreur', 'error');
  }
}

// ─── SUPPRIMER ────────────────────────────────────────────────────────────────
async function deleteCommande(id) {
  if (!confirm(`Supprimer définitivement la commande #${id} ?`)) return;
  const res = await api('delete_commande', 'POST', { id });
  if (res.success) { toast('Commande supprimée'); loadCommandes(); }
  else toast(res.error || 'Erreur', 'error');
}

function closeModal() {
  document.getElementById('modal-commande').classList.remove('open');
}
document.getElementById('modal-commande').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeModal();
});
document.getElementById('modal-detail').addEventListener('click', e => {
  if (e.target === e.currentTarget) e.currentTarget.classList.remove('open');
});

// ═══════════════════════════════════════════════════════════════════════════════
// PANIERS
// ═══════════════════════════════════════════════════════════════════════════════
async function loadPaniers() {
  const grid = document.getElementById('panier-grid');
  grid.innerHTML = '<div class="loading" style="grid-column:1/-1"><div class="spinner"></div></div>';
  const data = await api('paniers');
  if (!Array.isArray(data) || !data.length) {
    grid.innerHTML = '<div class="empty" style="grid-column:1/-1;padding:60px">🛒 Aucun panier actif pour le moment</div>';
    return;
  }
  grid.innerHTML = data.map((p,i) => `
    <div class="panier-card">
      <div class="panier-count-badge">${p.nb_articles} article(s)</div>
      <div class="panier-num">Panier ${i+1}</div>
      <div class="panier-session">${p.session_id}</div>
      <div class="panier-articles">🛍️ ${p.articles||'—'}</div>
      <div class="panier-footer">
        <div class="panier-total">${fmt(p.total)}</div>
        <div class="panier-meta"><div>${fmtDT(p.derniere_activite)}</div></div>
      </div>
    </div>`).join('');
}

// ═══════════════════════════════════════════════════════════════════════════════
// PLANTES
// ═══════════════════════════════════════════════════════════════════════════════
async function loadPlantes() {
  const tbody = document.getElementById('tbody-plantes');
  tbody.innerHTML = '<tr><td colspan="6"><div class="loading"><div class="spinner"></div></div></td></tr>';
  const data = await api('plantes');
  if (!Array.isArray(data) || !data.length) {
    tbody.innerHTML = '<tr><td colspan="6"><div class="empty">Aucune plante</div></td></tr>';
    return;
  }
  tbody.innerHTML = data.map(p => `
    <tr>
      <td><span style="font-family:monospace;font-size:11px;color:var(--text4)">#${p.id}</span></td>
      <td><strong style="color:var(--text)">${p.nom||'—'}</strong></td>
      <td><span class="badge badge-grey">${p.categorie||'—'}</span></td>
      <td><strong style="color:var(--green)">${fmt(p.prix)}</strong></td>
      <td style="color:${parseInt(p.stock||0)<10?'var(--orange)':'var(--text2)'}">${p.stock??'—'}</td>
      <td>${stockBadge(p.stock)}</td>
    </tr>`).join('');
}

// ═══════════════════════════════════════════════════════════════════════════════
// PRODUITS
// ═══════════════════════════════════════════════════════════════════════════════
async function loadProduits() {
  const tbody = document.getElementById('tbody-produits');
  tbody.innerHTML = '<tr><td colspan="6"><div class="loading"><div class="spinner"></div></div></td></tr>';
  const data = await api('produits');
  if (!Array.isArray(data) || !data.length) {
    tbody.innerHTML = '<tr><td colspan="6"><div class="empty">Aucun produit</div></td></tr>';
    return;
  }
  tbody.innerHTML = data.map(p => `
    <tr>
      <td><span style="font-family:monospace;font-size:11px;color:var(--text4)">#${p.id}</span></td>
      <td><strong style="color:var(--text)">${p.nom||'—'}</strong></td>
      <td><span class="badge badge-grey">${p.categorie||'—'}</span></td>
      <td><strong style="color:var(--green)">${fmt(p.prix)}</strong></td>
      <td style="color:${parseInt(p.stock||0)<10?'var(--orange)':'var(--text2)'}">${p.stock??'—'}</td>
      <td>${stockBadge(p.stock)}</td>
    </tr>`).join('');
}

// ═══════════════════════════════════════════════════════════════════════════════
// USERS
// ═══════════════════════════════════════════════════════════════════════════════
let usersTimer;
function searchUsers() {
  clearTimeout(usersTimer);
  usersTimer = setTimeout(loadUsers, 300);
}

async function loadUsers() {
  const tbody = document.getElementById('tbody-users');
  tbody.innerHTML = '<tr><td colspan="6"><div class="loading"><div class="spinner"></div></div></td></tr>';
  const s = document.getElementById('search-users')?.value || '';
  const data = await api('users', 'GET', null, '&search=' + encodeURIComponent(s));
  if (!Array.isArray(data) || !data.length) {
    tbody.innerHTML = '<tr><td colspan="6"><div class="empty">Aucun utilisateur</div></td></tr>';
    return;
  }
  tbody.innerHTML = data.map(u => `
    <tr>
      <td><span style="font-family:monospace;font-size:11px;color:var(--text4)">#${u.id}</span></td>
      <td><strong style="color:var(--text)">${u.prenom||''} ${u.nom||''}</strong></td>
      <td style="color:var(--text3);font-size:12px">${u.email||'—'}</td>
      <td><span class="badge badge-blue">${u.nb_commandes||0}</span></td>
      <td><strong style="color:var(--green)">${fmt(u.total_depense)}</strong></td>
      <td style="color:var(--text3);font-size:12px">${fmtDate(u.created_at)}</td>
    </tr>`).join('');
}

// ─── INIT ─────────────────────────────────────────────────────────────────────
loadStats();
</script>
</body>
</html>