import { instance, getHeader } from "../instance";

const headers = getHeader();

export type CreateReportDto = {
  car_id: string;
  reason: string;
  description: string;
};

export type CreateReportResponse = {
  car_id: string;
  reason: string;
  description: string;
};

export const userReportCar = async (
  createReportDto: CreateReportDto
): Promise<CreateReportResponse> => {
  const { data } = await instance.post("/user/report", createReportDto, headers);
  return data as CreateReportResponse;
};
export const listReport = async (): Promise<any> => {
  const { data } = await instance.get("/user/report", headers);
  return data;
};


