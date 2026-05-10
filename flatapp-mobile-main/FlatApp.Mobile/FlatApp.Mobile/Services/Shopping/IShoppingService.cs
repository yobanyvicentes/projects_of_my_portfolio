using FlatApp.Mobile.Models.Shopping;

namespace FlatApp.Mobile.Services.Shopping;

public interface IShoppingService
{
    Task<ShoppingResponse?> GetAsync(CancellationToken cancellationToken = default);

    Task<ShoppingItem?> CreateAsync(CreateShoppingItemRequest request, CancellationToken cancellationToken = default);

    Task<ShoppingItem?> UpdateAsync(int id, UpdateShoppingItemRequest request, CancellationToken cancellationToken = default);

    Task<ShoppingItem?> DeactivateAsync(int id, CancellationToken cancellationToken = default);

    Task<ShoppingItem?> ReactivateAsync(int id, CancellationToken cancellationToken = default);

    Task<ShoppingPurchaseResponse?> PurchaseAsync(int id, PurchaseShoppingItemRequest request, CancellationToken cancellationToken = default);
}
