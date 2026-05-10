namespace FlatApp.Mobile.Models.Receipts;

public sealed record ReceiptsResponse(
    IReadOnlyList<ReceiptItem> Receipts,
    ReceiptQuota Quota);

public sealed record ReceiptResponse(ReceiptItem Receipt, string? Message = null);

public sealed record ReceiptItem(
    int Id,
    string Title,
    decimal? Amount,
    int? ExpenseId,
    string? FileType,
    string? Url,
    string? UploadedByName,
    string? CreatedAt);

public sealed record ReceiptQuota(
    int Used,
    int Remaining,
    int Limit,
    int MaxFileSizeKb);
