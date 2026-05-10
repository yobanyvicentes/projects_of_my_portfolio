using FlatApp.Mobile.Models.Receipts;

namespace FlatApp.Mobile.Services.Receipts;

public interface IReceiptService
{
    Task<ReceiptsResponse?> GetAsync(CancellationToken cancellationToken = default);

    Task<ReceiptItem?> UploadAsync(
        string title,
        decimal? amount,
        string fileName,
        string contentType,
        Stream fileStream,
        int? expenseId = null,
        CancellationToken cancellationToken = default);

    Task DeleteAsync(int id, CancellationToken cancellationToken = default);
}
