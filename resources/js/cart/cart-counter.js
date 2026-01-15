import axios from "axios";

export async function updateCartCounter() {
    const cartCounter = document.querySelector(".cart-counter");

    if (!cartCounter) return;

    try {
        const response = await axios.get("/cart/count");
        const count = response.data.count;

        cartCounter.textContent = count;

    } catch (error) {
        console.error("Error fetching cart count:", error);
    }
}
