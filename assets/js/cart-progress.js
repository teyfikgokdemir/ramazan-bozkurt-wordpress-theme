(function(){
  const threshold=4000;
  function parseMoney(text){
    if(!text) return 0;
    let s=text.replace(/[^0-9,.-]/g,'').trim();
    if(s.includes('.')&&s.includes(',')) s=s.replace(/\./g,'').replace(',','.');
    else if(s.includes(',')) s=s.replace(',','.');
    return parseFloat(s)||0;
  }
  function findSubtotal(){
    const rows=[...document.querySelectorAll('.wc-block-components-totals-item')];
    for(const row of rows){
      const label=(row.querySelector('.wc-block-components-totals-item__label')?.textContent||'').trim().toLowerCase();
      if(label.includes('ara toplam')||label==='subtotal'){
        return parseMoney(row.querySelector('.wc-block-components-formatted-money-amount,.wc-block-formatted-money-amount')?.textContent||'');
      }
    }
    return 0;
  }
  function translate(){
    document.querySelectorAll('.wc-block-components-totals-item__label,.wc-block-components-totals-shipping__via').forEach(el=>{
      const t=el.textContent.trim();
      if(t==='Free shipping') el.textContent='Ücretsiz Kargo';
      if(t==='Shipping') el.textContent='Kargo';
      if(t==='Subtotal') el.textContent='Ara Toplam';
    });
  }
  function render(){
    translate();
    const subtotal=findSubtotal();
    const totals=document.querySelector('.wc-block-cart__totals-title')?.parentElement||document.querySelector('.wc-block-components-sidebar');
    if(!totals||!subtotal) return;
    let box=document.querySelector('.rb-shipping-progress--block');
    if(!box){
      box=document.createElement('div');
      box.className='rb-shipping-progress rb-shipping-progress--block';
      totals.insertBefore(box,totals.firstChild);
    }
    const remaining=Math.max(0,threshold-subtotal);
    const percent=Math.min(100,(subtotal/threshold)*100);
    const money=new Intl.NumberFormat('tr-TR',{style:'currency',currency:'TRY',maximumFractionDigits:0}).format(remaining);
    box.innerHTML=remaining>0
      ? '<div class="rb-shipping-progress__copy"><strong>Ücretsiz kargoya '+money+' kaldı.</strong><span>Sepetinize '+money+' daha ürün ekleyin; 4.000 TL ve üzeri siparişlerde kargo ücretsiz.</span></div><div class="rb-shipping-progress__track"><span style="width:'+percent+'%"></span></div>'
      : '<div class="rb-shipping-progress__copy"><strong>Ücretsiz kargo kazandınız.</strong><span>Sepetiniz 4.000 TL ücretsiz kargo sınırını geçti.</span></div><div class="rb-shipping-progress__track"><span style="width:100%"></span></div>';
  }
  let timer;
  const run=()=>{clearTimeout(timer);timer=setTimeout(render,120)};
  document.addEventListener('DOMContentLoaded',run);
  new MutationObserver(run).observe(document.documentElement,{subtree:true,childList:true,characterData:true});
})();