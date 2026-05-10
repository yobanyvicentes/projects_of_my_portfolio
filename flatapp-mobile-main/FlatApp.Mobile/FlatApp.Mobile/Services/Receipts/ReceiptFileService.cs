using FlatApp.Mobile.Services.Api;

namespace FlatApp.Mobile.Services.Receipts;

public sealed class ReceiptFileService(HttpClient httpClient) : IReceiptFileService
{
    public async Task<string> DownloadToCacheAsync(string url, CancellationToken cancellationToken = default)
    {
        using var response = await httpClient.GetAsync(url, cancellationToken);

        if (!response.IsSuccessStatusCode)
        {
            var body = await response.Content.ReadAsStringAsync(cancellationToken);
            throw new ApiException((int)response.StatusCode, body);
        }

        var extension = ExtensionFrom(response.Content.Headers.ContentType?.MediaType, url);
        var fileName = $"flatnz-receipt-{DateTimeOffset.UtcNow.ToUnixTimeMilliseconds()}{extension}";
        var filePath = Path.Combine(FileSystem.CacheDirectory, fileName);

        await using var source = await response.Content.ReadAsStreamAsync(cancellationToken);
        await using var destination = File.Create(filePath);
        await source.CopyToAsync(destination, cancellationToken);

        return filePath;
    }

    private static string ExtensionFrom(string? mediaType, string url)
    {
        var pathExtension = Path.GetExtension(new Uri(url).AbsolutePath);

        if (!string.IsNullOrWhiteSpace(pathExtension))
        {
            return pathExtension;
        }

        return mediaType?.ToLowerInvariant() switch
        {
            "image/jpeg" => ".jpg",
            "image/png" => ".png",
            "application/pdf" => ".pdf",
            _ => ".bin",
        };
    }
}
