namespace FlatApp.Mobile.Models.Finance;

public sealed record FinanceResponse(
    FinanceSummary Summary,
    IReadOnlyList<FinanceMember> Members,
    IReadOnlyList<FinanceExpense> Expenses,
    IReadOnlyList<FinanceSettlement> Settlements,
    IReadOnlyList<FinanceBalance> Balances,
    IReadOnlyList<FinanceOptimizedPayment> OptimizedPayments,
    IReadOnlyList<FinanceCategoryTotal> Categories);

public sealed record FinanceSummary(
    decimal TotalExpenses,
    int Transactions,
    decimal AvgPerUser,
    decimal YouOwe,
    decimal YouAreOwed,
    decimal NetBalance,
    FinanceUser? TopSpender);

public sealed record FinanceUser(int Id, string Name, string Email);

public sealed record FinanceMember(int Id, string Name, string Email, string Role);

public sealed record FinanceExpense(
    int Id,
    string Title,
    decimal Amount,
    string Category,
    int PaidBy,
    string? PayerName,
    string? ExpenseDate,
    string? CreatedAt,
    IReadOnlyList<FinanceExpenseSplit> Splits,
    FinanceReceipt? Receipt);

public sealed record FinanceExpenseSplit(int UserId, string? Name, decimal Amount);

public sealed record FinanceReceipt(int Id, string Title, decimal Amount, string? FileType);

public sealed record FinanceSettlement(
    int Id,
    FinanceUser FromUser,
    FinanceUser ToUser,
    decimal Amount,
    string? PaidAt,
    string? Notes,
    string? ProofType,
    string? CreatedAt);

public sealed record FinanceBalance(FinanceUser User, decimal Balance);

public sealed record FinanceOptimizedPayment(FinanceUser From, FinanceUser To, decimal Amount);

public sealed record FinanceCategoryTotal(string Category, decimal Amount);

public sealed record FinanceExpenseResponse(FinanceExpense Expense, string? Message = null);

public sealed record FinanceSettlementResponse(FinanceSettlement Settlement, string? Message = null);

public sealed record CreateFinanceExpenseRequest(
    string Title,
    decimal Amount,
    string Category,
    int PaidBy,
    IReadOnlyList<int> Participants,
    string SplitType,
    IReadOnlyDictionary<int, decimal> Splits,
    string? ExpenseDate);

public sealed record CreateFinanceSettlementRequest(
    int? FromUserId,
    int ToUserId,
    decimal Amount,
    string? PaidAt,
    string? Notes);
