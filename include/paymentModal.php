<style>
/* Modal overlay */
.custom-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
}

/* Modal content */
.pin-modal-content {
    background-color: #fff;
    padding: 30px 20px;
    border-radius: 15px;
    width: 90%;
    max-width: 400px;
    text-align: center;
    position: relative;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    animation: slideDown 0.3s ease-out;
}

/* Animation */
@keyframes slideDown {
    from {transform: translateY(-50px); opacity: 0;}
    to {transform: translateY(0); opacity: 1;}
}

/* Close button */
.custom-close {
    position: absolute;
    right: 15px;
    top: 10px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    color: #888;
}
.custom-close:hover {
    color: #333;
}

/* Instruction text */
.pin-instruction {
    font-size: 14px;
    color: #555;
    margin-bottom: 20px;
}

/* PIN input container */
.pin-input-container {
        display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 15px;
}

/* Individual PIN boxes */
.pin-box {
    width: 50px;
    height: 50px;
    font-size: 24px;
    text-align: center;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    outline: none;
    transition: border-color 0.2s;
}

.pin-box:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0,123,255,0.5);
}

/* Error message */
.pin-error {
    color: red;
    font-size: 14px;
    margin-bottom: 10px;
}

/* Confirm button */
.btn-pin {
    width: 100%;
    padding: 12px;
    background-color: #2F3A53;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 10px;
    cursor: pointer;
    transition: background-color 0.2s;
}

</style>



<!-- PIN Confirmation Modal -->
<!-- PIN Confirmation Modal -->
<div id="pinModal" class="custom-modal">
    <div class="custom-modal-content pin-modal-content">
        <span class="custom-close">&times;</span>
        <h3 style="font-size: 18px;">Enter PIN to Confirm Withdrawal</h3>
        <p class="pin-instruction">Please enter your 4-digit PIN to authorize this transaction.</p>
        
        <div class="pin-input-container">
            <input type="password" maxlength="1" class="pin-box" />
            <input type="password" maxlength="1" class="pin-box" />
            <input type="password" maxlength="1" class="pin-box" />
            <input type="password" maxlength="1" class="pin-box" />
        </div>
        
        <div id="pinError" class="pin-error"></div>
        <button id="confirmPinBtn" class="btn-pin">Confirm</button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const theForm = document.querySelector("#withdrawalForm") || document.querySelector("#transferForm");
    const modal = document.getElementById('pinModal');
    const openBtn = document.getElementById('openPinModal');
    const closeBtn = modal.querySelector('.custom-close');
    const confirmBtn = document.getElementById('confirmPinBtn');
    const pinBoxes = Array.from(document.querySelectorAll(".pin-box"));
    const pinError = document.getElementById('pinError');

    // Open modal
    openBtn.addEventListener('click', function() {
        pinBoxes.forEach(box => box.value = "");
        pinError.textContent = "";
        modal.style.display = "flex";
        pinBoxes[0].focus();
    });

    // Close modal
    closeBtn.addEventListener('click', function() {
        modal.style.display = "none";
    });

    window.addEventListener('click', function(e) {
        if (e.target == modal) modal.style.display = "none";
    });

    // Auto-focus next box
    pinBoxes.forEach((box, idx) => {
        box.addEventListener("input", function() {
            if (box.value.length === 1 && idx < pinBoxes.length -1) {
                pinBoxes[idx + 1].focus();
            }
        });
        box.addEventListener("keydown", function(e) {
            if (e.key === "Backspace" && box.value === "" && idx > 0) {
                pinBoxes[idx -1].focus();
            }
        });
    });

    // Confirm PIN
    confirmBtn.addEventListener('click', function() {
        const pin = pinBoxes.map(box => box.value).join("");
        if (!/^\d{4}$/.test(pin)) {
            pinError.textContent = "Please enter a 4-digit PIN.";
            return;
        }

        // Add PIN to form as hidden input
        let hiddenPin = theForm.querySelector(".pin-input");
        console.log("Hidden PIN input:", hiddenPin);
        
        hiddenPin.value = pin;
        modal.style.display = "none";
        theForm.submit();
    });
});
</script>