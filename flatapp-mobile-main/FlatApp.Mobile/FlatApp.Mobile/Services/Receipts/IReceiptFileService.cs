namespace FlatApp.Mobile.Services.Receipts;

public interface IReceiptFileService
{
    Task<string> DownloadToCacheAsync(string url, CancellationToken cancellationToken = default);
}
