document.addEventListener("DOMContentLoaded",()=>{
    const increase = document.getElementById('btn-increase');
    const decrease = document.getElementById('btn-decrease');
    const inputQuantity = document.getElementById('quantity-product');
    const maxValue = Number(inputQuantity.max);

    console.log(typeof maxValue);

    increase.addEventListener('click',()=>{
        const currentValue = Number(inputQuantity.value);
        if(currentValue < maxValue){
            inputQuantity.value = currentValue + 1;
        }
    });

    decrease.addEventListener('click',()=>{
        const currentValue = Number(inputQuantity.value);
        if(currentValue > 1){
            inputQuantity.value = currentValue - 1;
        }
    });
})
