using FlatApp.Mobile.Models.Receipts;
using FlatApp.Mobile.Services.Api;

namespace FlatApp.Mobile.Services.Receipts;

public sealed class ReceiptService(IApiClient apiClient) : IReceiptService
{
    public Task<ReceiptsResponse?> GetAsync(CancellationToken cancellationToken = default)
    {
        return apiClient.GetAsync<ReceiptsResponse>("receipts", cancellationToken);
    }

    public async Task<ReceiptItem?> UploadAsync(
        string title,
        decimal? amount,
        string fileName,
        string contentType,
        Stream fileStream,
        int? expenseId = null,
        CancellationToken cancellationToken = default)
    {
        using var content = new MultipartFormDataContent();
        content.Add(new StringContent(title), "title");

        if (amount is not null)
        {
            content.Add(new StringContent(amount.Value.ToString(System.Globalization.CultureInfo.InvariantCulture)), "amount");
        }

        if (expenseId is not null)
        {
            content.Add(new StringContent(expenseId.Value.ToString(System.Globalization.CultureInfo.InvariantCulture)), "expense_id");
        }

        var fileContent = new StreamContent(fileStream);
        fileContent.Headers.ContentType = new System.Net.Http.Headers.MediaTypeHeaderValue(contentType);
        content.Add(fileContent, "file", fileName);

        var response = await apiClient.PostMultipartAsync<ReceiptResponse>("receipts", content, cancellationToken);

        return response?.Receipt;
    }

    public Task DeleteAsync(int id, CancellationToken cancellationToken = default)
    {
        return apiClient.DeleteAsync($"receipts/{id}", cancellationToken);
    }
}
