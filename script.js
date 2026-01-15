document.addEventListener('DOMContentLoaded', function () {


  const addCarrinho = document.querySelectorAll('.cart.card-add-btn');
  const sidebar = document.getElementById('sidebar');
  const closeSidebar = document.querySelector('.sidebar-close');
  const navCart = document.querySelector('.navcart');
  const cartItemLista = document.querySelector('.cart-item');
  const cartTotal = document.querySelector('.cart-total');
  const compraBtn = document.querySelector('.compra-btn');
  const cartQ = document.querySelector('.navcart span');
  
  let cartItemsArray = [];
  let cartTotalValue = 0;

  // Variáveis do Menu Mobile e Filtros
  const openFilterBtn = document.getElementById('open-filter-btn');
  const closeFilterBtn = document.getElementById('close-filter-btn');
  const filterSidebar = document.querySelector('.filter-sidebar');
  const filterOverlay = document.getElementById('filter-overlay'); 

  const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
  const closeMobileMenuBtn = document.getElementById('close-mobile-menu-btn');
  const mobileMenuPanel = document.getElementById('mobile-menu-panel');
  const mobileDropdownToggle = document.querySelector('.mobile-dropdown .dropdown-toggle');

 
  function tratarPreco(valor) {
      if (!valor) return 0;
      
      // Se já for número, retorna ele mesmo
      if (typeof valor === 'number') return valor;
      
      // Se for texto, limpa espaços, remove R$ e troca vírgula por ponto
      let stringTratada = valor.toString().replace('R$', '').trim().replace(',', '.');
      
      let numero = parseFloat(stringTratada);
      return isNaN(numero) ? 0 : numero;
  }

  function mostrarMensagemAdicionado() {
    const notification = document.createElement('div');
    notification.classList.add('cart-notification');
    notification.innerHTML = `<i class='bx bxs-check-circle'></i> Produto adicionado no carrinho`;
    document.body.appendChild(notification);
    setTimeout(() => notification.classList.add('show'), 10);
    setTimeout(() => {
      notification.classList.remove('show');
      setTimeout(() => document.body.contains(notification) && document.body.removeChild(notification), 500);
    }, 3000);
  }

  function loadCartFromStorage() {
    const storedCart = localStorage.getItem('cartItems');
    
    if (storedCart) {
      try {
          cartItemsArray = JSON.parse(storedCart).map(item => ({
            ...item,
            // Garante o tratamento do preço ao carregar
            preco: tratarPreco(item.preco), 
            quantidade: parseInt(item.quantidade) || 1,
            imagem: item.imagem || 'imagens/logo.png'
          }));
      } catch (e) {
          console.log("Erro ao carregar carrinho:", e);
          cartItemsArray = [];
      }
    }
    
    updateCartUI(); 
  }

  function saveCartToStorage() {
    localStorage.setItem('cartItems', JSON.stringify(cartItemsArray));
    localStorage.setItem('cartTotal', cartTotalValue.toString());
  }

  // --- GERA O HTML DO CARRINHO LATERAL (SIDEBAR) ---
  function updateCartItemLista() {
    if (!cartItemLista) return;
    cartItemLista.innerHTML = ''; 
    
    if (cartItemsArray.length === 0) {
      cartItemLista.innerHTML = '<div style="padding:20px; text-align:center; color:#666;">Seu carrinho está vazio.</div>';
      return;
    }

    cartItemsArray.forEach((item, index) => {
      const cartItemHTML = `
        <div class="cart-box" data-index="${index}">
            <img src="${item.imagem}" alt="Produto" class="cart-img">
            
            <div class="detail-box">
                <div class="cart-product-title">${item.nome}</div>
                <div class="cart-price">R$ ${(item.preco * item.quantidade).toFixed(2).replace('.', ',')}</div>
                
                <div class="cart-quantity">
                     <button type="button" class="decrement">-</button>
                     <input type="number" value="${item.quantidade}" class="cart-quantity-input" readonly>
                     <button type="button" class="increment">+</button>
                </div>
            </div>

            <i class='bx bxs-trash-alt cart-remove'></i> 
        </div>`;
        
      cartItemLista.innerHTML += cartItemHTML;
    });
  }

  function updateCartUI() {
    // 1. Recalcula totais
    cartTotalValue = cartItemsArray.reduce((acc, item) => acc + (item.preco * item.quantidade), 0);
    const totalQuantity = cartItemsArray.reduce((sum, item) => sum + item.quantidade, 0);

    // 2. Atualiza sidebar e ícone do menu
    if (cartQ) cartQ.textContent = totalQuantity;
    // Exibe o total formatado com vírgula (ex: 14,90)
    if (cartTotal) cartTotal.textContent = `R$ ${cartTotalValue.toFixed(2).replace('.', ',')}`;
    
    if (cartItemLista) updateCartItemLista();

    // 3. Atualiza os CARDS NA VITRINE
    const allProductBoxes = document.querySelectorAll('.box');
    
    allProductBoxes.forEach(box => {
        const productName = box.dataset.nome; 
        if (!productName) return; 
        
        const itemInCart = cartItemsArray.find(item => item.nome === productName);

        if (itemInCart) {
            box.classList.add('in-cart'); 
            const qtyValue = box.querySelector('.qty-value');
            if (qtyValue) qtyValue.textContent = itemInCart.quantidade;
        } else {
            box.classList.remove('in-cart'); 
            const qtyValue = box.querySelector('.qty-value');
            if (qtyValue) qtyValue.textContent = '1'; 
        }
    });

    saveCartToStorage();
  }

  if (cartItemLista) {
    cartItemLista.addEventListener('click', function (event) {
      const target = event.target;
      const itemDiv = target.closest('.cart-box');
      if (!itemDiv) return;
      
      const index = parseInt(itemDiv.dataset.index, 10);
      let item = cartItemsArray[index];

      // Remover
      if (target.classList.contains('cart-remove') || target.closest('.cart-remove')) {
        cartItemsArray.splice(index, 1);
      } 
      // Aumentar (+)
      else if (target.classList.contains('increment')) {
        item.quantidade++;
      } 
      // Diminuir (-)
      else if (target.classList.contains('decrement')) {
        if (item.quantidade > 1) {
          item.quantidade--;
        } else {
          cartItemsArray.splice(index, 1);
        }
      }
      
      updateCartUI(); 
    });
  }

  addCarrinho.forEach((button) => {
    if (button.classList.contains('disponivel')) {
      button.addEventListener('click', () => {
        const productBox = button.closest('.box');
        
        const itemName = productBox.dataset.nome; 
        // USO DA FUNÇÃO TRATAR PREÇO
        const itemPrice = tratarPreco(productBox.dataset.preco);
        
        const imgElement = productBox.querySelector('img');
        const itemImg = imgElement ? imgElement.src : 'imagens/logo.png';

        if (isNaN(itemPrice) || !itemName) return;

        const existeItem = cartItemsArray.find((i) => i.nome === itemName);

        if (existeItem) {
          existeItem.quantidade++;
        } else {
          cartItemsArray.push({ nome: itemName, preco: itemPrice, quantidade: 1, imagem: itemImg });
        }

        updateCartUI(); 
        mostrarMensagemAdicionado();
      });
    }
  });

  document.addEventListener('click', function(event) {
      
      // 1. Botão (+) no Card
      const btnAdd = event.target.closest('.qty-btn.qty-add');
      if (btnAdd) {
          const box = btnAdd.closest('.box');
          if (box) {
              const productName = box.dataset.nome;
              const itemInCart = cartItemsArray.find(item => item.nome === productName);
              
              if (itemInCart) {
                  itemInCart.quantidade++;
                  updateCartUI(); 
              }
          }
      }

      // 2. Botão (Lixeira/Remover) no Card
      const btnRemove = event.target.closest('.qty-btn.qty-remove');
      if (btnRemove) {
          const box = btnRemove.closest('.box');
          if (box) {
              const productName = box.dataset.nome;
              const itemInCart = cartItemsArray.find(item => item.nome === productName);
              
              if (itemInCart) {
                  if (itemInCart.quantidade > 1) {
                      itemInCart.quantidade--;
                  } else {
                      const index = cartItemsArray.indexOf(itemInCart);
                      if (index > -1) cartItemsArray.splice(index, 1);
                  }
                  updateCartUI(); 
              }
          }
      }
  });

  
  if (navCart) navCart.addEventListener('click', () => sidebar.classList.toggle('open'));
  if (closeSidebar) closeSidebar.addEventListener('click', () => sidebar.classList.remove('open'));

  // --- FINALIZAR COMPRA (WHATSAPP) ---
  if (compraBtn) {
    compraBtn.addEventListener('click', () => {
      if (cartItemsArray.length === 0) {
        alert('O carrinho está vazio!');
        return;
      }
      let mensagem = '*Olá! Meu pedido de hoje:*\n\n';
      cartItemsArray.forEach(item => {
        mensagem += `• ${item.quantidade}x ${item.nome} - R$${(item.preco * item.quantidade).toFixed(2).replace('.', ',')}\n`;
      });
      mensagem += `\n*Total:* R$${cartTotalValue.toFixed(2).replace('.', ',')}\n\n*Data:* ${new Date().toLocaleString('pt-BR')}`;
      const numeroMercado = '5561996576211';
      const url = `https://api.whatsapp.com/send?phone=${numeroMercado}&text=${encodeURIComponent(mensagem)}`;
      window.open(url, '_blank');
      
      cartItemsArray = [];
      cartTotalValue = 0;
      updateCartUI();
      localStorage.removeItem('cartItems');
    });
  }

 
  if (openFilterBtn) openFilterBtn.addEventListener('click', () => {
    if (filterSidebar) filterSidebar.classList.add('open');
    if (filterOverlay) filterOverlay.classList.add('active');
  });

  if (closeFilterBtn) closeFilterBtn.addEventListener('click', () => {
    if (filterSidebar) filterSidebar.classList.remove('open');
    if (filterOverlay) filterOverlay.classList.remove('active');
  });

  if (mobileMenuToggle) mobileMenuToggle.addEventListener('click', () => {
    if (mobileMenuPanel) mobileMenuPanel.classList.add('open');
    if (filterOverlay) filterOverlay.classList.add('active');
  });

  function closeMobileMenu() {
    if (mobileMenuPanel) mobileMenuPanel.classList.remove('open');
    if (filterOverlay) filterOverlay.classList.remove('active');
  }

  if (closeMobileMenuBtn) closeMobileMenuBtn.addEventListener('click', closeMobileMenu);

  if (filterOverlay) {
    filterOverlay.addEventListener('click', () => {
      if (mobileMenuPanel && mobileMenuPanel.classList.contains('open')) closeMobileMenu();
      if (filterSidebar && filterSidebar.classList.contains('open')) {
        filterSidebar.classList.remove('open');
        filterOverlay.classList.remove('active');
      }
    });
  }

  if (mobileDropdownToggle) {
    mobileDropdownToggle.addEventListener('click', function () {
      const menu = this.closest('.mobile-menu-content').querySelector('.mobile-menu-nav');
      menu.style.display = (menu.style.display === 'flex') ? 'none' : 'flex';
    });
  }

  // --- QUICK VIEW (MODAL) ---
  const quickViewBtns = document.querySelectorAll(".quick-view-btn");
  const quickViewOverlay = document.getElementById("quick-view-overlay");
  const quickViewCloseBtn = document.getElementById("quick-view-close");
  const modalImg = document.getElementById("quick-view-img");
  const modalNome = document.getElementById("quick-view-nome");
  const modalPreco = document.getElementById("quick-view-preco");
  const modalAddToCartBtn = document.querySelector("#quick-view-modal .add-to-cart-btn");

  if (quickViewBtns.length > 0 && quickViewOverlay) {
      quickViewBtns.forEach(btn => {
          btn.addEventListener("click", function() {
              const box = this.closest('.box'); 
              const nome = this.getAttribute("data-nome");
              const precoTexto = this.getAttribute("data-preco");
              const descricao = this.getAttribute("data-descricao");
              const imagem = this.getAttribute("data-imagem");
              // USO DA FUNÇÃO TRATAR PREÇO
              const precoRaw = tratarPreco(box.dataset.preco);

              if (modalNome) modalNome.textContent = nome;
              if (modalPreco) modalPreco.innerHTML = `${precoTexto} <span>${descricao}</span>`;
              if (modalImg) modalImg.src = imagem;
              
              if (modalAddToCartBtn) {
                  modalAddToCartBtn.dataset.nome = nome;
                  modalAddToCartBtn.dataset.preco = precoRaw;
                  modalAddToCartBtn.dataset.imagem = imagem; 
              }

              quickViewOverlay.classList.add("active");
          });
      });

      function closeQuickViewModal() {
          quickViewOverlay.classList.remove("active");
      }
      if (quickViewCloseBtn) quickViewCloseBtn.addEventListener("click", closeQuickViewModal);
      quickViewOverlay.addEventListener("click", function(event) {
          if (event.target === quickViewOverlay) closeQuickViewModal();
      });

      if (modalAddToCartBtn) {
          modalAddToCartBtn.addEventListener("click", function() {
              const itemName = this.dataset.nome;
              // USO DA FUNÇÃO TRATAR PREÇO
              const itemPrice = tratarPreco(this.dataset.preco);
              const itemImg = this.dataset.imagem || 'imagens/logo.png';

              if (isNaN(itemPrice) || !itemName) return;

              const existeItem = cartItemsArray.find((i) => i.nome === itemName);
              if (existeItem) {
                  existeItem.quantidade++;
              } else {
                  cartItemsArray.push({ nome: itemName, preco: itemPrice, quantidade: 1, imagem: itemImg });
              }
              updateCartUI();
              mostrarMensagemAdicionado();
              closeQuickViewModal(); 
          });
      }
  }

  // Inicializa
  loadCartFromStorage();
});