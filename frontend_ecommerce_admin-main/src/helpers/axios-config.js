import { isExpired } from "react-jwt";
import axios from "axios";

let token = "";
try {
  const tokenString = localStorage.getItem("token");
  console.log("tokenString:", tokenString);
  const userToken = JSON.parse(tokenString);
  const isMyTokenExpired = isExpired(userToken);
  console.log(isMyTokenExpired)
  if (isMyTokenExpired) {
    localStorage.setItem("token", "")
  }
  token = userToken;
} catch (error) {
  token = "";
  console.log(token);
}

export const axiosInstance = axios.create({
  baseURL: "https://back-admin-ecommerce.onrender.com/",
  headers: {
    "access-token": token,
  },
});
