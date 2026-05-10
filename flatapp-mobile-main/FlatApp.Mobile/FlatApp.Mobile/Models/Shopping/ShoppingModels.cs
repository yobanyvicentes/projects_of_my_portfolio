namespace FlatApp.Mobile.Models.Shopping;

public sealed record ShoppingResponse(
    IReadOnlyList<ShoppingItem> Items,
    IReadOnlyList<ShoppingItem> ActiveItems,
    IReadOnlyList<ShoppingItem> InactiveItems);

public sealed record ShoppingItemResponse(ShoppingItem Item, string? Message = null);

public sealed record ShoppingPurchaseResponse(ShoppingItem Item, ShoppingExpense Expense, string? Message = null);

public sealed record ShoppingItem(
    int Id,
    int FlatId,
    int? AddedBy,
    string? AddedByName,
    string Name,
    string? Quantity,
    string Status,
    string? PurchasedAt,
    string? CreatedAt,
    string? UpdatedAt);

public sealed record ShoppingExpense(
    int Id,
    string Title,
    decimal Amount,
    string Category,
    int PaidBy,
    string? ExpenseDate);

public sealed record CreateShoppingItemRequest(string Name, string? Quantity);

public sealed record UpdateShoppingItemRequest(string Name, string? Quantity);

public sealed record PurchaseShoppingItemRequest(
    string Name,
    decimal Amount,
    int PaidBy,
    IReadOnlyList<int> Participants,
    string SplitType,
    IReadOnlyDictionary<int, decimal> Splits,
    string PurchasedAt);
