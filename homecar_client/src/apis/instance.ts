import axios from "axios";

const AUTHENTICATED_KEY = "access_token";

export const instance = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    "Content-Type": "application/json",
  },
});

export const instanceUpload = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    'content-type': 'multipart/form-data'
  },
});

export const getHeader = () => {
  let token = localStorage.getItem(AUTHENTICATED_KEY);

  const headers = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  return headers;
};
